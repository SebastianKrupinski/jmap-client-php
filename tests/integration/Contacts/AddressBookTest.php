<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Tests\Integration\Contacts;

use JmapClient\Client;
use JmapClient\Requests\Contacts\AddressBookGet as AddressBookGetRequest;
use JmapClient\Requests\Contacts\AddressBookSet as AddressBookSetRequest;
use JmapClient\Responses\Contacts\AddressBookGet as AddressBookGetResponse;
use JmapClient\Responses\Contacts\AddressBookParameters;
use JmapClient\Responses\Contacts\AddressBookSet as AddressBookSetResponse;
use JmapClient\Session\Account;
use JmapClient\Tests\Integration\ClientFactory;
use PHPUnit\Framework\TestCase;

class AddressBookTest extends TestCase
{
    private Client $client;
    private Account $account;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = ClientFactory::instance('service1');
        $this->client->connect();
        $this->account = $this->client->sessionAccountDefault('contacts');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        // construct list request
        $r0 = new AddressBookGetRequest($this->account->id());
        // transceive
        $bundle = $this->client->perform([$r0]);
        $response = $bundle->first();
        if (!($response instanceof AddressBookGetResponse)) {
            return;
        }
        $commands = [];
        // construct deletion requests
        foreach ($response->objects() as $collection) {
            if (str_starts_with($collection->label(), 'Test Address Book ') ||
                str_starts_with($collection->label(), 'Modified Address Book ') ||
                str_starts_with($collection->label(), 'To Delete Address Book ')) {
                $rq = new AddressBookSetRequest($this->account->id());
                $rq->delete($collection->id());
                $commands[] = $rq;
            }
        }
        // transceive
        if (!empty($commands)) {
            $this->client->perform($commands);
        }
    }

    public function testAddressBookSetCreate(): void
    {
        // construct create request
        $createId = uniqid();
        $r0 = new AddressBookSetRequest($this->account->id());
        $p0 = $r0->create($createId);
        $p0->label('Test Address Book ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify create response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(AddressBookSetResponse::class, $bundle->first());
        $result = $bundle->first()->createSuccess($createId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testAddressBookSetUpdate(): void
    {
        // construct create request
        $collectionId = uniqid();
        $r0 = new AddressBookSetRequest($this->account->id());
        $p0 = $r0->create($collectionId);
        $p0->label('Test Address Book ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract collection id
        $result = $bundle->first()->createSuccess($collectionId);
        $collectionId = $result['id'];

        // construct modify request
        $r1 = new AddressBookSetRequest($this->account->id());
        $p1 = $r1->update($collectionId);
        $p1->label('Modified Address Book ' . time());

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify modify response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(AddressBookSetResponse::class, $bundle->first());
        $result = $bundle->first()->updateSuccess($collectionId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testAddressBookSetDelete(): void
    {
        // construct create request
        $collectionId = uniqid();
        $r0 = new AddressBookSetRequest($this->account->id());
        $p0 = $r0->create($collectionId);
        $p0->label('To Delete Address Book ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract collection id
        $result = $bundle->first()->createSuccess($collectionId);
        $collectionId = $result['id'];

        // construct delete request
        $r1 = new AddressBookSetRequest($this->account->id());
        $r1->delete($collectionId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify delete response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(AddressBookSetResponse::class, $bundle->first());
        $result = $bundle->first()->deleteSuccess($collectionId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testAddressBookGetSpecific(): void
    {
        // construct create request
        $collectionId = uniqid();
        $collectionLabel = 'Test Address Book ' . time();
        $r0 = new AddressBookSetRequest($this->account->id());
        $p0 = $r0->create($collectionId);
        $p0->label($collectionLabel);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract collection id
        $result = $bundle->first()->createSuccess($collectionId);
        $collectionId = $result['id'];

        // construct fetch request
        $r1 = new AddressBookGetRequest($this->account->id());
        $r1->target($collectionId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(AddressBookGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(AddressBookParameters::class, $result);
        $this->assertEquals($collectionId, $result->id());
        $this->assertEquals($collectionLabel, $result->label());
    }

    public function testAddressBookGetAll(): void
    {
        // construct create request
        $createId = uniqid();
        $r0 = new AddressBookSetRequest($this->account->id());
        $p0 = $r0->create($createId);
        $p0->label('Test Address Book ' . time());

        // transceive
        $this->client->perform([$r0]);

        // construct fetch request
        $r0 = new AddressBookGetRequest($this->account->id());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(AddressBookGetResponse::class, $bundle->first());
        $results = $bundle->first()->objects();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        foreach ($results as $result) {
            $this->assertInstanceOf(AddressBookParameters::class, $result);
        }
    }

    public function testAddressBookGetWithProperties(): void
    {
        // construct create request
        $createId = uniqid();
        $r0 = new AddressBookSetRequest($this->account->id());
        $p0 = $r0->create($createId);
        $p0->label('Test Address Book ' . time());

        // transceive
        $this->client->perform([$r0]);

        // construct fetch request
        $r0 = new AddressBookGetRequest($this->account->id());
        $r0->property('id', 'name');

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(AddressBookGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(AddressBookParameters::class, $result);
        $this->assertNotEmpty($result->id());
        $this->assertNotEmpty($result->label());
        $this->assertNull($result->description());
    }
}
