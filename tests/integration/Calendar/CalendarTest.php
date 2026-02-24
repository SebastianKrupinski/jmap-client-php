<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Tests\Integration\Calendar;

use JmapClient\Client;
use JmapClient\Requests\Calendar\CalendarGet as CalendarGetRequest;
use JmapClient\Requests\Calendar\CalendarSet as CalendarSetRequest;
use JmapClient\Responses\Calendar\CalendarGet as CalendarGetResponse;
use JmapClient\Responses\Calendar\CalendarParameters;
use JmapClient\Responses\Calendar\CalendarSet as CalendarSetResponse;
use JmapClient\Session\Account;
use JmapClient\Tests\Integration\ClientFactory;
use PHPUnit\Framework\TestCase;

class CalendarTest extends TestCase
{
    private Client $client;
    private Account $account;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = ClientFactory::instance('service1');
        $this->client->connect();
        $this->account = $this->client->sessionAccountDefault('calendars');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        // construct list request
        $r0 = new CalendarGetRequest($this->account->id());
        // transceive
        $bundle = $this->client->perform([$r0]);
        $response = $bundle->first();
        if (!($response instanceof CalendarGetResponse)) {
            return;
        }
        $commands = [];
        // construct deletion requests
        foreach ($response->objects() as $collection) {
            if (str_starts_with($collection->label(), 'Test Calendar ') ||
                str_starts_with($collection->label(), 'Modified Calendar ') ||
                str_starts_with($collection->label(), 'To Delete Calendar ')) {
                $rq = new CalendarSetRequest($this->account->id());
                $rq->delete($collection->id());
                $commands[] = $rq;
            }
        }
        // transceive
        if (!empty($commands)) {
            $this->client->perform($commands);
        }
    }

    public function testCalendarSetCreate(): void
    {
        // construct create request
        $createId = uniqid();
        $r0 = new CalendarSetRequest($this->account->id());
        $p0 = $r0->create($createId);
        $p0->label('Test Calendar ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify create response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(CalendarSetResponse::class, $bundle->first());
        $result = $bundle->first()->createSuccess($createId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testCalendarSetUpdate(): void
    {
        // construct create request
        $collectionId = uniqid();
        $r0 = new CalendarSetRequest($this->account->id());
        $p0 = $r0->create($collectionId);
        $p0->label('Test Calendar ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract collection id
        $result = $bundle->first()->createSuccess($collectionId);
        $collectionId = $result['id'];

        // construct modify request
        $r1 = new CalendarSetRequest($this->account->id());
        $p1 = $r1->update($collectionId);
        $p1->label('Modified Calendar ' . time());

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify modify response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(CalendarSetResponse::class, $bundle->first());
        $result = $bundle->first()->updateSuccess($collectionId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testCalendarSetDelete(): void
    {
        // construct create request
        $collectionId = uniqid();
        $r0 = new CalendarSetRequest($this->account->id());
        $p0 = $r0->create($collectionId);
        $p0->label('To Delete Calendar ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract collection id
        $result = $bundle->first()->createSuccess($collectionId);
        $collectionId = $result['id'];

        // construct delete request
        $r1 = new CalendarSetRequest($this->account->id());
        $r1->delete($collectionId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify delete response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(CalendarSetResponse::class, $bundle->first());
        $result = $bundle->first()->deleteSuccess($collectionId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testCalendarGetSpecific(): void
    {
        // construct create request
        $collectionId = uniqid();
        $collectionLabel = 'Test Calendar ' . time();
        $r0 = new CalendarSetRequest($this->account->id());
        $p0 = $r0->create($collectionId);
        $p0->label($collectionLabel);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract collection id
        $result = $bundle->first()->createSuccess($collectionId);
        $collectionId = $result['id'];

        // construct fetch request
        $r1 = new CalendarGetRequest($this->account->id());
        $r1->target($collectionId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(CalendarGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(CalendarParameters::class, $result);
        $this->assertEquals($collectionId, $result->id());
        $this->assertEquals($collectionLabel, $result->label());
    }

    public function testCalendarGetAll(): void
    {
        // construct create request
        $createId = uniqid();
        $r0 = new CalendarSetRequest($this->account->id());
        $p0 = $r0->create($createId);
        $p0->label('Test Calendar ' . time());

        // transceive
        $this->client->perform([$r0]);

        // construct fetch request
        $r0 = new CalendarGetRequest($this->account->id());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(CalendarGetResponse::class, $bundle->first());
        $results = $bundle->first()->objects();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        foreach ($results as $result) {
            $this->assertInstanceOf(CalendarParameters::class, $result);
        }
    }

    public function testCalendarGetWithProperties(): void
    {
        // construct create request
        $createId = uniqid();
        $r0 = new CalendarSetRequest($this->account->id());
        $p0 = $r0->create($createId);
        $p0->label('Test Calendar ' . time());

        // transceive
        $this->client->perform([$r0]);

        // construct fetch request
        $r0 = new CalendarGetRequest($this->account->id());
        $r0->property('id', 'name');

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(CalendarGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(CalendarParameters::class, $result);
        $this->assertNotEmpty($result->id());
        $this->assertNotEmpty($result->label());
        $this->assertNull($result->description());
    }
}
