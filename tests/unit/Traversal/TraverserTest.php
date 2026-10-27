<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Traversal;

use JmapClient\Client;
use JmapClient\Exceptions\MethodException;
use JmapClient\Exceptions\QueryChangedException;
use JmapClient\Requests\Mail\MailChanges;
use JmapClient\Requests\Mail\MailGet;
use JmapClient\Requests\Mail\MailQuery;
use JmapClient\Requests\Request;
use JmapClient\Responses\ResponseBundle;
use JmapClient\Responses\ResponseException;
use JmapClient\Session\Session;
use JmapClient\Traversal\Traverser;
use PHPUnit\Framework\TestCase;

class TraverserTest extends TestCase
{
    /** @var list<string> */
    private array $ids = [];
    private int $queryLimit = 500;
    private bool $calculateTotal = true;
    private int $objectsInGet = 500;
    private ?Session $session = null;
    /** @var list<string> query states returned per query call, the last one repeats */
    private array $queryStates = ['q1'];
    /** @var list<array> scripted Email/changes responses */
    private array $changes = [];
    /** @var list<list<array>> commands of every performed request */
    private array $requests = [];

    private function traverser(): Traverser
    {
        $client = $this->createMock(Client::class);
        $client->method('sessionStatus')->willReturn(true);
        $client->method('sessionData')->willReturnCallback(fn () => $this->session);
        $client->method('perform')->willReturnCallback(fn (array $commands) => $this->respond($commands));

        return new Traverser($client);
    }

    /**
     * @param list<Request> $commands
     */
    private function respond(array $commands): ResponseBundle
    {
        $serialized = array_map(static fn (Request $command) => json_decode($command->jsonEncode(), true), $commands);
        $this->requests[] = $serialized;
        $queried = [];
        $responses = [];
        foreach ($serialized as [$operation, $arguments, $identifier]) {
            switch ($operation) {
                case 'Email/query':
                    $position = $arguments['position'] ?? 0;
                    $limit = min($arguments['limit'] ?? PHP_INT_MAX, $this->queryLimit);
                    $state = count($this->queryStates) > 1 ? array_shift($this->queryStates) : $this->queryStates[0];
                    $result = ['queryState' => $state, 'position' => $position, 'ids' => array_slice($this->ids, $position, $limit)];
                    if ($limit < ($arguments['limit'] ?? PHP_INT_MAX)) {
                        $result['limit'] = $limit;
                    }
                    if ($this->calculateTotal && ($arguments['calculateTotal'] ?? false)) {
                        $result['total'] = count($this->ids);
                    }
                    $queried[$identifier] = $result['ids'];
                    $responses[] = [$operation, $result, $identifier];
                    break;
                case 'Email/get':
                    $ids = isset($arguments['#ids']) ? $queried[$arguments['#ids']['resultOf']] : $arguments['ids'];
                    if (count($ids) > $this->objectsInGet) {
                        $responses[] = ['error', ['type' => ResponseException::REQUEST_TOO_LARGE], $identifier];
                        break;
                    }
                    $list = array_map(static fn (string $id) => ['id' => $id], $ids);
                    $responses[] = [$operation, ['state' => 's1', 'list' => $list, 'notFound' => []], $identifier];
                    break;
                case 'Email/changes':
                    $responses[] = array_shift($this->changes) + [2 => $identifier];
                    break;
            }
        }

        return new ResponseBundle(['methodResponses' => $responses, 'sessionState' => 'session']);
    }

    private function query(): MailQuery
    {
        return new MailQuery('account');
    }

    private function get(): MailGet
    {
        return (new MailGet('account'))->property('id');
    }

    private function changes(): MailChanges
    {
        return new MailChanges('account');
    }

    /**
     * @return list<string>
     */
    private function idsOf(iterable $pages): array
    {
        $ids = [];
        foreach ($pages as $page) {
            foreach ($page as $entry) {
                $ids[] = is_string($entry) ? $entry : $entry->id();
            }
        }
        return $ids;
    }

    private function useIds(int $count): void
    {
        $this->ids = array_map(static fn (int $n) => 'm' . $n, range(1, $count));
    }

    public function testQueryReturnsEveryPage(): void
    {
        $this->useIds(1204);

        $result = $this->traverser()->query($this->query());

        $this->assertSame('q1', $result->queryState());
        $this->assertNull($result->state());
        $this->assertSame(1204, $result->total());
        $this->assertCount(1, $this->requests);
        $this->assertSame($this->ids, $this->idsOf($result->pages()));
        $this->assertCount(3, $this->requests);
        $this->assertSame(500, $this->requests[1][0][1]['position']);
        $this->assertSame(1000, $this->requests[2][0][1]['position']);
        $this->assertTrue($this->requests[2][0][1]['calculateTotal']);
    }

    public function testQueryKeepsTheCallerPageSize(): void
    {
        $this->useIds(250);

        $result = $this->traverser()->query($this->query()->limitAbsolute(null, 100));

        $this->assertSame($this->ids, $this->idsOf($result->pages()));
        $this->assertCount(3, $this->requests);
        $this->assertSame(100, $this->requests[2][0][1]['limit']);
    }

    public function testQueryPagesWithoutTotalWhileTheServerCapsTheLimit(): void
    {
        $this->useIds(1000);
        $this->calculateTotal = false;

        $result = $this->traverser()->query($this->query());

        $this->assertSame($this->ids, $this->idsOf($result->pages()));
        $this->assertCount(3, $this->requests);
    }

    public function testQueryWithoutResults(): void
    {
        $result = $this->traverser()->query($this->query());

        $this->assertSame(0, $result->total());
        $this->assertSame([], iterator_to_array($result->pages(), false));
        $this->assertCount(1, $this->requests);
    }

    public function testQueryFailsWhenTheResultChangesWhilePaging(): void
    {
        $this->useIds(600);
        $this->queryStates = ['q1', 'q2'];

        $result = $this->traverser()->query($this->query());

        $this->expectException(QueryChangedException::class);
        iterator_to_array($result->pages(), false);
    }

    public function testQueryRejectsASecondIteration(): void
    {
        $result = $this->traverser()->query($this->query());
        iterator_to_array($result->pages(), false);

        $this->expectException(\LogicException::class);
        iterator_to_array($result->pages(), false);
    }

    public function testQueryThrowsMethodErrors(): void
    {
        $client = $this->createMock(Client::class);
        $client->method('perform')->willReturn(new ResponseBundle(['methodResponses' => [
            ['error', ['type' => ResponseException::UNSUPPORTED_FILTER, 'description' => 'nope'], 'r0'],
        ]]));

        try {
            (new Traverser($client))->query($this->query());
            $this->fail('Expected a method exception');
        } catch (MethodException $e) {
            $this->assertSame(ResponseException::UNSUPPORTED_FILTER, $e->getType());
            $this->assertSame('unsupportedFilter: nope', $e->getMessage());
        }
    }

    public function testQueryAndFetchReturnsEveryObject(): void
    {
        $this->useIds(1204);

        $result = $this->traverser()->queryAndFetch($this->query(), $this->get());

        $this->assertSame('s1', $result->state());
        $this->assertSame('q1', $result->queryState());
        $this->assertSame([], $this->requests[0][0][1]['ids']);
        $this->assertSame(500, $this->requests[0][1][1]['limit']);
        $this->assertSame($this->ids, $this->idsOf($result->pages()));
        // each further query page is followed by one get request
        $this->assertCount(5, $this->requests);
    }

    public function testQueryAndFetchCapsTheQueryAtTheGetLimit(): void
    {
        $this->useIds(700);
        $this->objectsInGet = 200;
        $this->session = new Session(['capabilities' => ['urn:ietf:params:jmap:core' => ['maxObjectsInGet' => 200]]]);

        $result = $this->traverser()->queryAndFetch($this->query(), $this->get());

        $this->assertSame($this->ids, $this->idsOf($result->pages()));
        // pages of 200, 200, 200 and 100 ids, each retrieved by a single get
        $this->assertCount(7, $this->requests);
        foreach ([1, 3, 5] as $index) {
            $this->assertSame(200, $this->requests[$index][0][1]['limit']);
            $this->assertCount(1, $this->requests[$index + 1]);
        }
    }

    public function testQueryAndFetchKeepsASmallerCallerLimit(): void
    {
        $this->useIds(250);

        $result = $this->traverser()->queryAndFetch($this->query()->limitAbsolute(null, 100), $this->get());

        $this->assertSame($this->ids, $this->idsOf($result->pages()));
        $this->assertSame(100, $this->requests[0][1][1]['limit']);
        $this->assertCount(5, $this->requests);
    }

    public function testQueryAndFetchCapsALargerCallerLimit(): void
    {
        $this->useIds(10);

        $this->traverser()->queryAndFetch($this->query()->limitAbsolute(null, 1000), $this->get());

        $this->assertSame(500, $this->requests[0][1][1]['limit']);
    }

    public function testQueryAndFetchThrowsWhenTheServerRejectsItsAdvertisedLimit(): void
    {
        $this->useIds(300);
        $this->objectsInGet = 100;
        $this->session = new Session(['capabilities' => ['urn:ietf:params:jmap:core' => ['maxObjectsInGet' => 300]]]);

        try {
            $this->traverser()->queryAndFetch($this->query(), $this->get());
            $this->fail('Expected a method exception');
        } catch (MethodException $e) {
            $this->assertSame(ResponseException::REQUEST_TOO_LARGE, $e->getType());
        }
    }

    public function testConnectsBeforeReadingTheServerLimits(): void
    {
        $this->useIds(10);
        $calls = [];
        $connected = false;
        $client = $this->createMock(Client::class);
        $client->method('sessionStatus')->willReturnCallback(function () use (&$connected) {
            return $connected;
        });
        $client->expects($this->once())->method('connect')->willReturnCallback(function () use (&$calls, &$connected) {
            $calls[] = 'connect';
            $connected = true;
            $this->session = new Session(['capabilities' => ['urn:ietf:params:jmap:core' => ['maxObjectsInGet' => 50]]]);
            return $this->session;
        });
        $client->method('sessionData')->willReturnCallback(fn () => $this->session);
        $client->method('perform')->willReturnCallback(function (array $commands) use (&$calls) {
            $calls[] = 'perform';
            return $this->respond($commands);
        });

        $result = (new Traverser($client))->queryAndFetch($this->query(), $this->get());
        iterator_to_array($result->pages(), false);

        $this->assertSame(['connect', 'perform'], $calls);
        $this->assertSame(50, $this->requests[0][1][1]['limit']);
    }

    public function testFetchHalvesTheChunkSizeWhenTheRequestIsTooLarge(): void
    {
        $this->useIds(300);
        $this->objectsInGet = 100;
        // server advertises a higher limit than it accepts
        $this->session = new Session(['capabilities' => ['urn:ietf:params:jmap:core' => ['maxObjectsInGet' => 300]]]);

        $pages = $this->traverser()->fetch($this->ids, $this->get());

        $this->assertSame($this->ids, $this->idsOf($pages));
        // 300 rejected, 150 rejected, then one request of four calls of 75 ids
        $this->assertSame(300, count($this->requests[0][0][1]['ids']));
        $this->assertSame(150, count($this->requests[1][0][1]['ids']));
        $this->assertSame(75, count($this->requests[2][0][1]['ids']));
        $this->assertCount(3, $this->requests);
        $this->assertCount(4, $this->requests[2]);
    }

    public function testRequestTemplatesAreNotModified(): void
    {
        $this->useIds(1204);
        $query = $this->query();
        $query->filter()->in('inbox');
        $get = $this->get();
        $expectedQuery = $query->jsonEncode();
        $expectedGet = $get->jsonEncode();

        $result = $this->traverser()->queryAndFetch($query, $get);
        iterator_to_array($result->pages(), false);

        $this->assertSame($expectedQuery, $query->jsonEncode());
        $this->assertSame($expectedGet, $get->jsonEncode());
        // later query pages keep the template filter
        $this->assertSame('inbox', $this->requests[1][0][1]['filter']['inMailbox']);
        $this->assertSame(500, $this->requests[1][0][1]['position']);
    }

    public function testLaterPagesIgnoreTemplateChangesAfterTheCall(): void
    {
        $this->useIds(600);
        $query = $this->query();
        $query->filter()->in('inbox');

        $result = $this->traverser()->query($query);
        $query->filter()->in('archive');
        iterator_to_array($result->pages(), false);

        $this->assertSame('inbox', $this->requests[1][0][1]['filter']['inMailbox']);
    }

    public function testCallIdentifiersAreUniqueWithinARequest(): void
    {
        $this->useIds(300);
        $this->session = new Session(['capabilities' => ['urn:ietf:params:jmap:core' => ['maxObjectsInGet' => 100]]]);

        iterator_to_array($this->traverser()->fetch($this->ids, $this->get()), false);

        $identifiers = array_column($this->requests[0], 2);
        $this->assertCount(3, $identifiers);
        $this->assertSame($identifiers, array_unique($identifiers));
    }

    public function testFetchWithoutIdentifiers(): void
    {
        $this->assertSame([], iterator_to_array($this->traverser()->fetch([], $this->get()), false));
        $this->assertSame([], $this->requests);
    }

    public function testChangesFollowsMoreChanges(): void
    {
        $this->changes = [
            ['Email/changes', ['oldState' => 's1', 'newState' => 's2', 'hasMoreChanges' => true, 'created' => ['a'], 'updated' => [], 'destroyed' => []]],
            ['Email/changes', ['oldState' => 's2', 'newState' => 's3', 'hasMoreChanges' => false, 'created' => [], 'updated' => ['b'], 'destroyed' => ['c']]],
        ];

        $result = $this->traverser()->changes($this->changes(), 's1');
        $created = $updated = $deleted = [];
        foreach ($result->pages() as $page) {
            array_push($created, ...$page->created());
            array_push($updated, ...$page->updated());
            array_push($deleted, ...$page->deleted());
        }

        $this->assertSame(['a'], $created);
        $this->assertSame(['b'], $updated);
        $this->assertSame(['c'], $deleted);
        $this->assertSame('s3', $result->state());
        $this->assertSame('s1', $this->requests[0][0][1]['sinceState']);
        $this->assertSame('s2', $this->requests[1][0][1]['sinceState']);
    }

    public function testChangesStateRequiresCompleteIteration(): void
    {
        $this->changes = [
            ['Email/changes', ['oldState' => 's1', 'newState' => 's2', 'hasMoreChanges' => false]],
        ];

        $result = $this->traverser()->changes($this->changes(), 's1');

        $this->expectException(\LogicException::class);
        $result->state();
    }

    public function testChangesThrowsWhenTheStateCannotBeCalculated(): void
    {
        $this->changes = [
            ['error', ['type' => ResponseException::CANNOT_CALCULATE_CHANGES]],
        ];

        $this->expectException(MethodException::class);
        $this->traverser()->changes($this->changes(), 's0');
    }

    public function testChangesRejectsMoreChangesWithoutANewState(): void
    {
        $this->changes = [
            ['Email/changes', ['oldState' => 's1', 'newState' => 's1', 'hasMoreChanges' => true]],
        ];

        $result = $this->traverser()->changes($this->changes(), 's1');

        $this->expectException(\UnexpectedValueException::class);
        iterator_to_array($result->pages(), false);
    }
}
