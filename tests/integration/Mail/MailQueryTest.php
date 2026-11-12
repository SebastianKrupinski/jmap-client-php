<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Integration\Mail;

use JmapClient\Client;
use JmapClient\Requests\Mail\MailboxSet as MailboxSetRequest;
use JmapClient\Requests\Mail\MailQuery as MailQueryRequest;
use JmapClient\Requests\Mail\MailSet as MailSetRequest;
use JmapClient\Responses\Mail\MailQuery as MailQueryResponse;
use JmapClient\Responses\ResponseException;
use JmapClient\Session\Account;
use JmapClient\Tests\Integration\ClientFactory;
use PHPUnit\Framework\TestCase;

class MailQueryTest extends TestCase
{
    private Client $client;
    private Account $account;
    private string $mailboxId;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = ClientFactory::instance('service1');
        $this->client->connect();
        $this->account = $this->client->sessionAccountDefault('mail');

        // construct mailbox create request
        $mailboxId = uniqid();
        $r0 = new MailboxSetRequest($this->account->id());
        $r0->create($mailboxId)->label('Test Mail Query ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);
        $this->mailboxId = $bundle->first()->createSuccess($mailboxId)['id'];
    }

    public function tearDown(): void
    {
        parent::tearDown();
        // construct mailbox delete request (removes all contained emails)
        $r0 = new MailboxSetRequest($this->account->id());
        $r0->destroyContents(true);
        $r0->delete($this->mailboxId);

        // transceive
        $this->client->perform([$r0]);
    }

    private function createMail(string $subject, bool $flagged): string
    {
        $createId = uniqid();
        $r0 = new MailSetRequest($this->account->id());
        $p0 = $r0->create($createId);
        $p0->in($this->mailboxId);
        $p0->from('user1@example1.com', 'User One');
        $p0->to('user1@example1.com', 'User One');
        $p0->subject($subject);
        $p0->bodyTextPlain('Mail query test body.', 'body');
        if ($flagged) {
            $p0->flagged();
        }

        $bundle = $this->client->perform([$r0]);
        $result = $bundle->first()->createSuccess($createId);
        $this->assertNotNull($result, json_encode($bundle->first()->createFailure($createId)));
        return $result['id'];
    }

    private function query(MailQueryRequest $r0): array
    {
        $bundle = $this->client->perform([$r0]);
        $response = $bundle->first();
        $this->assertInstanceOf(
            MailQueryResponse::class,
            $response,
            $response instanceof ResponseException ? $response->type() . ': ' . $response->description() : ''
        );
        return $response->list();
    }

    private function sortedQuery(string $sort, bool $ascending): MailQueryRequest
    {
        $r0 = new MailQueryRequest($this->account->id());
        $r0->filter()->in($this->mailboxId);
        $r0->sort()->$sort('$flagged', $ascending);
        return $r0;
    }

    private function filteredQuery(string $filter): MailQueryRequest
    {
        $r0 = new MailQueryRequest($this->account->id());
        $r0->filter()->in($this->mailboxId)->$filter('$flagged');
        return $r0;
    }

    public static function sortProvider(): array
    {
        return [
            'keyword' => ['keyword'],
            'all in thread have keyword' => ['threadKeywordAll'],
            'some in thread have keyword' => ['threadKeywordSome'],
        ];
    }

    /**
     * @dataProvider sortProvider
     */
    public function testMailQuerySortByKeyword(string $sort): void
    {
        $plainId = $this->createMail('Plain Mail ' . time(), false);
        $flaggedId = $this->createMail('Flagged Mail ' . time(), true);

        // emails with the keyword sort after those without it when ascending
        $this->assertSame([$plainId, $flaggedId], $this->query($this->sortedQuery($sort, true)));
        // and before them when descending
        $this->assertSame([$flaggedId, $plainId], $this->query($this->sortedQuery($sort, false)));
    }

    public static function filterProvider(): array
    {
        return [
            'has keyword' => ['keywordPresent', true],
            'not keyword' => ['keywordAbsent', false],
            'all in thread have keyword' => ['threadKeywordAll', true],
            'some in thread have keyword' => ['threadKeywordSome', true],
            'none in thread have keyword' => ['threadKeywordNone', false],
        ];
    }

    /**
     * @dataProvider filterProvider
     */
    public function testMailQueryFilterByKeyword(string $filter, bool $matchesFlagged): void
    {
        $plainId = $this->createMail('Plain Mail ' . time(), false);
        $flaggedId = $this->createMail('Flagged Mail ' . time(), true);

        // each email is its own thread, so the thread filters match like the email filters
        $this->assertSame([$matchesFlagged ? $flaggedId : $plainId], $this->query($this->filteredQuery($filter)));
    }
}
