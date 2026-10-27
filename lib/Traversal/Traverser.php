<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Traversal;

use JmapClient\Client;
use JmapClient\Exceptions\MethodException;
use JmapClient\Exceptions\QueryChangedException;
use JmapClient\Requests\Request;
use JmapClient\Requests\RequestChanges;
use JmapClient\Requests\RequestGet;
use JmapClient\Requests\RequestQuery;
use JmapClient\Responses\ResponseChanges;
use JmapClient\Responses\ResponseException;
use JmapClient\Responses\ResponseGet;
use JmapClient\Responses\ResponseQuery;
use JmapClient\Session\CoreCapability;

/**
 * Retrieves complete query results, objects and changes in as many requests as the
 * server limits require (RFC 8620 sections 5.1, 5.2 and 5.5)
 *
 * Requests are used as templates, they are cloned for every call and never modified.
 */
class Traverser
{
    public const DEFAULT_OBJECTS_IN_GET = 500;
    public const DEFAULT_CALLS_IN_REQUEST = 16;

    private ?int $objectsInGetLimit = null;

    public function __construct(
        private Client $client,
    ) {
    }

    /**
     * Query every matching id
     *
     * @param RequestQuery $query query with filter and sort applied
     *
     * @return QueryResult<string> pages of ids
     *
     * @throws MethodException when the server rejects a call
     * @throws QueryChangedException when the result changes between pages
     */
    public function query(RequestQuery $query): QueryResult
    {
        $query = clone $query;
        $first = $this->expect($this->client->perform([(clone $query)->tally(true)])->response(0), ResponseQuery::class);

        return new QueryResult($first->state(), null, $first->total(), $this->queryPages($query, $first));
    }

    /**
     * Query every matching id and retrieve the matching objects
     *
     * The object state is captured before the query runs, so changes made while
     * the pages are retrieved are reported by the next /changes call.
     *
     * The query page size is capped at maxObjectsInGet, so every page is retrieved by a single get.
     *
     * @param RequestQuery $query query with filter and sort applied, a smaller limit is kept
     * @param RequestGet $get get request with properties applied
     *
     * @return QueryResult<mixed> pages of objects
     *
     * @throws MethodException when the server rejects a call
     * @throws QueryChangedException when the result changes between pages
     */
    public function queryAndFetch(RequestQuery $query, RequestGet $get): QueryResult
    {
        $query = clone $query;
        $get = clone $get;
        $limit = $query->jsonSerialize()[Request::REQUEST_COMMAND]['limit'] ?? null;
        if ($limit === null || $limit > $this->objectsInGetLimit()) {
            $query->limitAbsolute(null, $this->objectsInGetLimit());
        }
        $r0 = (clone $get)->target();
        $r1 = (clone $query)->tally(true);
        $r2 = (clone $get)->targetFromRequest($r1, '/ids');
        $bundle = $this->client->perform([$r0, $r1, $r2]);

        $snapshot = $this->expect($bundle->response(0), ResponseGet::class);
        $first = $this->expect($bundle->response(1), ResponseQuery::class);
        $objects = $this->expect($bundle->response(2), ResponseGet::class);

        return new QueryResult($first->state(), $snapshot->state(), $first->total(), $this->queryAndFetchPages($query, $get, $first, $objects));
    }

    /**
     * Retrieve objects by id in as many requests as the server limits require
     *
     * @param list<string> $identifiers
     * @param RequestGet $get get request with properties applied
     *
     * @return \Generator<int, list<mixed>> pages of objects
     *
     * @throws MethodException when the server rejects a call
     */
    public function fetch(array $identifiers, RequestGet $get): \Generator
    {
        return $this->fetchPages($identifiers, clone $get);
    }

    /**
     * Retrieve every change since the given state
     *
     * @param RequestChanges $changes changes request, optionally with maxChanges applied
     * @param string $state state to retrieve the changes since
     *
     * @throws MethodException when the server rejects a call, e.g. cannotCalculateChanges
     */
    public function changes(RequestChanges $changes, string $state): ChangesResult
    {
        $changes = clone $changes;
        $first = $this->expect($this->client->perform([(clone $changes)->state($state)])->response(0), ResponseChanges::class);

        return new ChangesResult($this->changesPages($changes, $first));
    }

    /**
     * @param list<string> $identifiers
     *
     * @return \Generator<int, list<mixed>>
     */
    private function fetchPages(array $identifiers, RequestGet $get): \Generator
    {
        $pending = array_values($identifiers);
        while ($pending !== []) {
            $size = $this->objectsInGetLimit();
            $chunks = array_slice(array_chunk($pending, $size), 0, $this->callsInRequestLimit());
            $requests = [];
            foreach ($chunks as $chunk) {
                $requests[] = (clone $get)->target(...$chunk);
            }
            $bundle = $this->client->perform($requests);
            foreach ($chunks as $index => $chunk) {
                $response = $bundle->response($index);
                if ($response instanceof ResponseException && $response->type() === ResponseException::REQUEST_TOO_LARGE && $size > 1) {
                    $this->objectsInGetLimit = intdiv($size, 2);
                    continue 2;
                }
                yield $this->expect($response, ResponseGet::class)->objects();
                $pending = array_slice($pending, count($chunk));
            }
        }
    }

    /**
     * @return \Generator<int, list<string>>
     */
    private function queryPages(RequestQuery $query, ResponseQuery $first): \Generator
    {
        $page = $first;
        while ($page->list() !== []) {
            yield $page->list();
            $position = $page->position() + count($page->list());
            if (!$this->hasMorePages($page, $position)) {
                return;
            }
            $page = $this->expect($this->client->perform([(clone $query)->limitAbsolute($position)->tally(true)])->response(0), ResponseQuery::class);
            if ($page->state() !== $first->state()) {
                throw new QueryChangedException($first->state(), $page->state());
            }
        }
    }

    /**
     * @param ResponseGet $objects objects of the first page, retrieved together with the query
     *
     * @return \Generator<int, list<mixed>>
     */
    private function queryAndFetchPages(RequestQuery $query, RequestGet $get, ResponseQuery $first, ResponseGet $objects): \Generator
    {
        foreach ($this->queryPages($query, $first) as $index => $ids) {
            if ($index === 0) {
                yield $objects->objects();
                continue;
            }
            foreach ($this->fetchPages($ids, $get) as $page) {
                yield $page;
            }
        }
    }

    /**
     * @return \Generator<int, ResponseChanges, mixed, string>
     */
    private function changesPages(RequestChanges $changes, ResponseChanges $first): \Generator
    {
        $page = $first;
        yield $page;
        while ($page->hasMore()) {
            if ($page->stateNew() === $page->stateOld()) {
                throw new \UnexpectedValueException('Server reported more changes without advancing the state ' . $page->stateNew());
            }
            $page = $this->expect($this->client->perform([(clone $changes)->state($page->stateNew())])->response(0), ResponseChanges::class);
            yield $page;
        }

        return $page->stateNew();
    }

    private function hasMorePages(ResponseQuery $page, int $position): bool
    {
        if ($page->total() !== null) {
            return $position < $page->total();
        }
        // without a total, a page capped by the server limit indicates more ids may follow
        return $page->limit() !== null && count($page->list()) >= $page->limit();
    }

    private function objectsInGetLimit(): int
    {
        return $this->objectsInGetLimit ??= $this->positiveOr(
            $this->coreCapability()?->maxObjectsInGet(),
            self::DEFAULT_OBJECTS_IN_GET,
        );
    }

    private function callsInRequestLimit(): int
    {
        return $this->positiveOr(
            $this->coreCapability()?->maxCallsInRequest(),
            self::DEFAULT_CALLS_IN_REQUEST,
        );
    }

    /**
     * Server limits, connecting first so the limits are known before the first request
     */
    private function coreCapability(): ?CoreCapability
    {
        if (!$this->client->sessionStatus()) {
            $this->client->connect();
        }

        return $this->client->sessionData()?->coreCapability();
    }

    private function positiveOr(?int $value, int $default): int
    {
        return $value !== null && $value > 0 ? $value : $default;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $class
     *
     * @return T
     *
     * @throws MethodException when the response is a method level error
     */
    private function expect(mixed $response, string $class): object
    {
        if ($response instanceof ResponseException) {
            throw new MethodException($response);
        }
        if (!$response instanceof $class) {
            throw new \UnexpectedValueException(sprintf('Expected %s response, got %s', $class, get_debug_type($response)));
        }

        return $response;
    }
}
