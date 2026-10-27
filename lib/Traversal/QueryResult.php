<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Traversal;

/**
 * Complete result of a query, the first page is retrieved up front and the remaining pages on iteration
 *
 * @template T
 */
class QueryResult
{
    private bool $consumed = false;

    /**
     * @param \Generator<int, list<T>> $pages
     */
    public function __construct(
        private string $queryState,
        private ?string $state,
        private ?int $total,
        private \Generator $pages,
    ) {
    }

    /**
     * Query state of the first page, usable with /queryChanges
     */
    public function queryState(): string
    {
        return $this->queryState;
    }

    /**
     * Object state captured before the query ran, usable with /changes, null when no objects were retrieved
     */
    public function state(): ?string
    {
        return $this->state;
    }

    /**
     * Number of ids in the complete result, null when the server did not calculate it
     */
    public function total(): ?int
    {
        return $this->total;
    }

    /**
     * Pages of the result, can only be iterated once
     *
     * @return \Generator<int, list<T>>
     *
     * @throws \LogicException when the pages were already iterated
     */
    public function pages(): \Generator
    {
        if ($this->consumed) {
            throw new \LogicException('Query result pages can only be iterated once');
        }
        $this->consumed = true;

        foreach ($this->pages as $page) {
            yield $page;
        }
    }
}
