<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Integration\Calendar;

use DateTimeImmutable;
use DateTimeZone;
use JmapClient\Client;
use JmapClient\Requests\Calendar\CalendarSet as CalendarSetRequest;
use JmapClient\Requests\Calendar\EventCopy as EventCopyRequest;
use JmapClient\Requests\Calendar\EventGet as EventGetRequest;
use JmapClient\Requests\Calendar\EventSet as EventSetRequest;
use JmapClient\Responses\Calendar\EventCopy as EventCopyResponse;
use JmapClient\Session\Account;
use JmapClient\Tests\Integration\ClientFactory;
use JmapClient\Tests\Integration\SharedAccountTrait;
use PHPUnit\Framework\TestCase;

class EventCopyTest extends TestCase
{
    use SharedAccountTrait;

    private Client $owner;
    private Account $ownerAccount;
    private ?string $ownerCalendarId = null;
    private Client $client;
    private Account $account;
    private ?string $calendarId = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->owner = ClientFactory::instance('service2');
        $this->owner->connect();
        $this->ownerAccount = $this->owner->sessionAccountDefault('calendars');
        $this->client = ClientFactory::instance('service1');
        $this->client->connect();
        $this->account = $this->client->sessionAccountDefault('calendars');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        if ($this->ownerCalendarId !== null) {
            $r0 = new CalendarSetRequest($this->ownerAccount->id());
            $r0->deleteContents(true);
            $r0->delete($this->ownerCalendarId);
            $this->owner->perform([$r0]);
        }
        if ($this->calendarId !== null) {
            $r1 = new CalendarSetRequest($this->account->id());
            $r1->deleteContents(true);
            $r1->delete($this->calendarId);
            $this->client->perform([$r1]);
        }
    }

    public function testEventCopyBetweenAccounts(): void
    {
        // construct create request for a calendar shared read only with the first user
        $calendarId = uniqid();
        $r0 = new CalendarSetRequest($this->ownerAccount->id());
        $p0 = $r0->create($calendarId);
        $p0->label('Test Event Copy Source ' . time());
        $p0->sharees($this->account->id())->read(true);

        // transceive
        $bundle = $this->owner->perform([$r0]);
        $result = $bundle->first()->createSuccess($calendarId);
        $this->assertNotNull($result, json_encode($bundle->first()->createFailure($calendarId)));
        $this->ownerCalendarId = $result['id'];

        // construct create request for the event in the shared calendar
        $eventId = uniqid();
        $label = 'Copy Event ' . time();
        $starts = new DateTimeImmutable('2026-12-01 10:00:00', new DateTimeZone('UTC'));
        $r1 = new EventSetRequest($this->ownerAccount->id());
        $p1 = $r1->create($eventId);
        $p1->in($this->ownerCalendarId);
        $p1->label($label);
        $p1->starts($starts);
        $p1->duration($starts->diff($starts->modify('+1 hour')));

        // transceive
        $bundle = $this->owner->perform([$r1]);
        $result = $bundle->first()->createSuccess($eventId);
        $this->assertNotNull($result, json_encode($bundle->first()->createFailure($eventId)));
        $sourceId = $result['id'];

        // reconnect as the first user and create the destination calendar
        [$this->client, $sharedAccount] = $this->connectToSharedAccount($this->ownerAccount->id());
        $destinationId = uniqid();
        $r2 = new CalendarSetRequest($this->account->id());
        $r2->create($destinationId)->label('Test Event Copy Destination ' . time());
        $bundle = $this->client->perform([$r2]);
        $this->calendarId = $bundle->first()->createSuccess($destinationId)['id'];

        // construct copy request from the shared account into the destination calendar
        $copyId = uniqid();
        $r3 = new EventCopyRequest($this->account->id());
        $r3->sourceAccount($sharedAccount->id());
        $r3->copy($copyId, $sourceId)->in($this->calendarId);

        // transceive
        $bundle = $this->client->perform([$r3]);

        // verify copy response
        $this->assertEquals(1, $bundle->tally());
        $response = $bundle->first();
        $this->assertInstanceOf(EventCopyResponse::class, $response, $this->describeError($response));
        $this->assertEquals($sharedAccount->id(), $response->sourceAccount());
        $result = $response->createSuccess($copyId);
        $this->assertNotNull($result, json_encode($response->createFailure($copyId)));
        $this->assertArrayHasKey('id', $result);

        // verify the copy is in the destination calendar
        $r4 = new EventGetRequest($this->account->id());
        $r4->target($result['id']);
        $bundle = $this->client->perform([$r4]);
        $event = $bundle->first()->object(0);
        $this->assertEquals($label, $event->label());
        $this->assertContains($this->calendarId, (array) $event->in());
    }
}
