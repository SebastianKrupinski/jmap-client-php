<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Tests\Integration\Mail;

use JmapClient\Client;
use JmapClient\Requests\Mail\MailboxGet as MailboxGetRequest;
use JmapClient\Requests\Mail\MailboxQuery as MailboxQueryRequest;
use JmapClient\Requests\Mail\MailboxSet as MailboxSetRequest;
use JmapClient\Responses\Mail\MailboxGet as MailboxGetResponse;
use JmapClient\Responses\Mail\MailboxParameters;
use JmapClient\Responses\Mail\MailboxQuery as MailboxQueryResponse;
use JmapClient\Responses\Mail\MailboxSet as MailboxSetResponse;
use JmapClient\Session\Account;
use JmapClient\Tests\Integration\ClientFactory;
use PHPUnit\Framework\TestCase;

class MailboxTest extends TestCase
{
    private Client $client;
    private Account $account;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = ClientFactory::instance('service1');
        $this->client->connect();
        $this->account = $this->client->sessionAccountDefault('mail');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        // construct list request
        $r0 = new MailboxGetRequest($this->account->id());
        // transceive
        $bundle = $this->client->perform([$r0]);
        $response = $bundle->first();
        if (!($response instanceof MailboxGetResponse)) {
            return;
        }
        $commands = [];
        // construct deletion requests
        foreach ($response->objects() as $collection) {
            if (str_starts_with($collection->label(), 'Test Mail Collection ') ||
                str_starts_with($collection->label(), 'Modified Mail Collection ') ||
                str_starts_with($collection->label(), 'To Delete Mail Collection ')) {
                $rq = new MailboxSetRequest($this->account->id());
                $rq->delete($collection->id());
                $commands[] = $rq;
            }
        }
        // transceive
        if (!empty($commands)) {
            $this->client->perform($commands);
        }
    }

    public function testMailboxSetCreate(): void
    {
        // construct create request
        $createId = uniqid();
        $r0 = new MailboxSetRequest($this->account->id());
        $p0 = $r0->create($createId);
        $p0->label('Test Mail Collection ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify create response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailboxSetResponse::class, $bundle->first());
        $result = $bundle->first()->createSuccess($createId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testMailboxSetUpdate(): void
    {
        // construct create request
        $collectionId = uniqid();
        $r0 = new MailboxSetRequest($this->account->id());
        $p0 = $r0->create($collectionId);
        $p0->label('Test Mail Collection ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract collection id
        $result = $bundle->first()->createSuccess($collectionId);
        $collectionId = $result['id'];

        // construct modify request
        $r1 = new MailboxSetRequest($this->account->id());
        $p1 = $r1->update($collectionId);
        $p1->label('Modified Mail Collection ' . time());

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify modify response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailboxSetResponse::class, $bundle->first());
        $result = $bundle->first()->updateSuccess($collectionId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testMailboxSetDelete(): void
    {
        // construct create request
        $collectionId = uniqid();
        $r0 = new MailboxSetRequest($this->account->id());
        $p0 = $r0->create($collectionId);
        $p0->label('To Delete Mail Collection ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract collection id
        $result = $bundle->first()->createSuccess($collectionId);
        $collectionId = $result['id'];

        // construct delete request
        $r1 = new MailboxSetRequest($this->account->id());
        $r1->delete($collectionId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify delete response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailboxSetResponse::class, $bundle->first());
        $result = $bundle->first()->deleteSuccess($collectionId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testMailboxGetSpecific(): void
    {
        // construct create request
        $collectionId = uniqid();
        $collectionLabel = 'Test Mail Collection ' . time();
        $r0 = new MailboxSetRequest($this->account->id());
        $p0 = $r0->create($collectionId);
        $p0->label($collectionLabel);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract collection id
        $result = $bundle->first()->createSuccess($collectionId);
        $collectionId = $result['id'];

        // construct fetch request
        $r1 = new MailboxGetRequest($this->account->id());
        $r1->target($collectionId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailboxGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(MailboxParameters::class, $result);
        $this->assertEquals($collectionId, $result->id());
        $this->assertEquals($collectionLabel, $result->label());
    }

    public function testMailboxGetAll(): void
    {
        // construct fetch request
        $r0 = new MailboxGetRequest($this->account->id());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailboxGetResponse::class, $bundle->first());
        $results = $bundle->first()->objects();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        foreach ($results as $result) {
            $this->assertInstanceOf(MailboxParameters::class, $result);
        }
    }

    public function testMailboxGetWithProperties(): void
    {
        // construct fetch request
        $r0 = new MailboxGetRequest($this->account->id());
        $r0->property('id', 'name');

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailboxGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(MailboxParameters::class, $result);
        $this->assertNotEmpty($result->id());
        $this->assertNotEmpty($result->label());
        $this->assertNull($result->role());
    }

    public function testMailboxQueryWithoutFilter(): void
    {
        // construct query request
        $r0 = new MailboxQueryRequest($this->account->id());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailboxQueryResponse::class, $bundle->first());
        $results = $bundle->first()->list();
        ;
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
    }

    public function testMailboxQueryWithMatchingFilter(): void
    {
        // construct query request
        $r0 = new MailboxQueryRequest($this->account->id());
        $r0->filter()->name('Inbox');

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailboxQueryResponse::class, $bundle->first());
        $results = $bundle->first()->list();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        $this->assertCount(1, $results);
    }

    public function testMailboxQueryWithNonMatchingFilter(): void
    {
        // construct query request
        $r0 = new MailboxQueryRequest($this->account->id());
        $r0->filter()->name('This Mailbox Does Not Exist ' . uniqid());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailboxQueryResponse::class, $bundle->first());
        $results = $bundle->first()->list();
        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    public function testMailboxQueryWithSorting(): void
    {
        // construct query request
        $r0 = new MailboxQueryRequest($this->account->id());
        $r0->sort()->name(true);

        // transceive ascending query
        $bundleAscending = $this->client->perform([$r0]);

        // verify ascending fetch response
        $this->assertEquals(1, $bundleAscending->tally());
        $this->assertInstanceOf(MailboxQueryResponse::class, $bundleAscending->first());
        $ascendingResults = $bundleAscending->first()->list();
        $this->assertIsArray($ascendingResults);
        $this->assertNotEmpty($ascendingResults);

        // construct descending query request
        $r1 = new MailboxQueryRequest($this->account->id());
        $r1->sort()->name(false);

        // transceive descending query
        $bundleDescending = $this->client->perform([$r1]);

        // verify descending fetch response
        $this->assertEquals(1, $bundleDescending->tally());
        $this->assertInstanceOf(MailboxQueryResponse::class, $bundleDescending->first());
        $descendingResults = $bundleDescending->first()->list();
        $this->assertIsArray($descendingResults);
        $this->assertNotEmpty($descendingResults);
        $this->assertCount(count($ascendingResults), $descendingResults);

        $firstAscending = $ascendingResults[0];
        $lastDescending = $descendingResults[count($descendingResults) - 1];
        $this->assertSame($firstAscending, $lastDescending);
    }
}
