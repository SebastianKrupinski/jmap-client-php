<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Tests\Integration\Calendar;

use DateTime;
use DateTimeZone;
use JmapClient\Client;
use JmapClient\Requests\Calendar\CalendarSet as CalendarSetRequest;
use JmapClient\Requests\Calendar\EventGet as EventGetRequest;
use JmapClient\Requests\Calendar\EventQuery as EventQueryRequest;
use JmapClient\Requests\Calendar\EventSet as EventSetRequest;
use JmapClient\Responses\Calendar\EventGet as EventGetResponse;
use JmapClient\Responses\Calendar\EventParameters;
use JmapClient\Responses\Calendar\EventQuery as EventQueryResponse;
use JmapClient\Responses\Calendar\EventSet as EventSetResponse;
use JmapClient\Session\Account;
use JmapClient\Tests\Integration\ClientFactory;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    private Client $client;
    private Account $account;
    private string $calendarId;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = ClientFactory::instance('service1');
        $this->client->connect();
        $this->account = $this->client->sessionAccountDefault('calendars');

        // construct calendar create request
        $calId = uniqid();
        $r0 = new CalendarSetRequest($this->account->id());
        $p0 = $r0->create($calId);
        $p0->label('Test Event Calendar ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract calendar id
        $result = $bundle->first()->createSuccess($calId);
        $this->calendarId = $result['id'];
    }

    public function tearDown(): void
    {
        parent::tearDown();
        // construct calendar delete request (removes all contained events)
        $r0 = new CalendarSetRequest($this->account->id());
        $r0->delete($this->calendarId);
        $r0->deleteContents(true);

        // transceive
        $this->client->perform([$r0]);
    }

    private function generateEventData(string $key): array
    {
        $baseTime = new DateTime('2025-11-15 10:00:00', new DateTimeZone('UTC'));

        return match ($key) {
            'singleton_part_day_no_participants' => [
                'label' => 'Singleton Part Day Event',
                'description' => 'A single event during the day without participants',
                'startsOn' => clone $baseTime,
                'endsOn' => (clone $baseTime)->modify('+2 hours'),
                'timeless' => false,
                'participants' => null,
                'recurrence' => null,
            ],
            'singleton_full_day_no_participants' => [
                'label' => 'Singleton Full Day Event',
                'description' => 'A single all-day event without participants',
                'startsOn' => new DateTime('2025-11-16 00:00:00', new DateTimeZone('UTC')),
                'endsOn' => new DateTime('2025-11-17 00:00:00', new DateTimeZone('UTC')),
                'timeless' => true,
                'participants' => null,
                'recurrence' => null,
            ],
            'singleton_part_day_with_participants' => [
                'label' => 'Meeting with Team',
                'description' => 'A meeting with multiple participants',
                'startsOn' => (clone $baseTime)->modify('+1 day'),
                'endsOn' => (clone $baseTime)->modify('+1 day +1 hour'),
                'timeless' => false,
                'participants' => 'default',
                'recurrence' => null,
            ],
            'singleton_full_day_with_participants' => [
                'label' => 'Company Event',
                'description' => 'An all-day company event with participants',
                'startsOn' => new DateTime('2025-11-18 00:00:00', new DateTimeZone('UTC')),
                'endsOn' => new DateTime('2025-11-19 00:00:00', new DateTimeZone('UTC')),
                'timeless' => true,
                'participants' => 'default',
                'recurrence' => null,
            ],
            'recurring_part_day_daily' => [
                'label' => 'Daily Standup',
                'description' => 'Daily team standup meeting',
                'startsOn' => (clone $baseTime)->modify('+2 days'),
                'endsOn' => (clone $baseTime)->modify('+2 days +30 minutes'),
                'timeless' => false,
                'participants' => null,
                'recurrence' => [
                    'precision' => 'daily',
                    'interval' => 1,
                    'iterations' => 10,
                ],
            ],
            'recurring_part_day_weekly' => [
                'label' => 'Weekly Review',
                'description' => 'Weekly team review meeting',
                'startsOn' => (clone $baseTime)->modify('+3 days'),
                'endsOn' => (clone $baseTime)->modify('+3 days +1 hour'),
                'timeless' => false,
                'participants' => null,
                'recurrence' => [
                    'precision' => 'weekly',
                    'interval' => 1,
                    'iterations' => 8,
                    'onDayOfWeek' => ['MO'],
                ],
            ],
            'recurring_part_day_monthly_absolute' => [
                'label' => 'Monthly Report',
                'description' => 'Monthly report meeting on the 15th',
                'startsOn' => new DateTime('2025-11-15 14:00:00', new DateTimeZone('UTC')),
                'endsOn' => new DateTime('2025-11-15 15:00:00', new DateTimeZone('UTC')),
                'timeless' => false,
                'participants' => null,
                'recurrence' => [
                    'precision' => 'monthly',
                    'interval' => 1,
                    'iterations' => 6,
                    'onDayOfMonth' => [15],
                ],
            ],
            'recurring_part_day_monthly_relative' => [
                'label' => 'Monthly Planning',
                'description' => 'Monthly planning on the 2nd Monday',
                'startsOn' => new DateTime('2025-11-10 10:00:00', new DateTimeZone('UTC')),
                'endsOn' => new DateTime('2025-11-10 11:00:00', new DateTimeZone('UTC')),
                'timeless' => false,
                'participants' => null,
                'recurrence' => [
                    'precision' => 'monthly',
                    'interval' => 1,
                    'iterations' => 6,
                    'onDayOfWeek' => ['MO'],
                    'onPosition' => [2],
                ],
            ],
            'recurring_part_day_yearly_absolute' => [
                'label' => 'Annual Review',
                'description' => 'Annual review on June 10th',
                'startsOn' => new DateTime('2026-06-10 09:00:00', new DateTimeZone('UTC')),
                'endsOn' => new DateTime('2026-06-10 17:00:00', new DateTimeZone('UTC')),
                'timeless' => false,
                'participants' => null,
                'recurrence' => [
                    'precision' => 'yearly',
                    'interval' => 1,
                    'iterations' => 3,
                    'onMonthOfYear' => [6],
                ],
            ],
            'recurring_part_day_yearly_relative' => [
                'label' => 'Annual Conference',
                'description' => 'Annual conference on the 3rd Wednesday of May',
                'startsOn' => new DateTime('2026-05-20 10:00:00', new DateTimeZone('UTC')),
                'endsOn' => new DateTime('2026-05-20 16:00:00', new DateTimeZone('UTC')),
                'timeless' => false,
                'participants' => null,
                'recurrence' => [
                    'precision' => 'yearly',
                    'interval' => 1,
                    'iterations' => 3,
                    'onDayOfWeek' => ['WE'],
                    'onPosition' => [3],
                    'onMonthOfYear' => [5],
                ],
            ],
            'recurring_full_day_daily' => [
                'label' => 'Daily Task Block',
                'description' => 'Daily full-day task block',
                'startsOn' => new DateTime('2025-11-20 00:00:00', new DateTimeZone('UTC')),
                'endsOn' => new DateTime('2025-11-21 00:00:00', new DateTimeZone('UTC')),
                'timeless' => true,
                'participants' => null,
                'recurrence' => [
                    'precision' => 'daily',
                    'interval' => 1,
                    'iterations' => 5,
                ],
            ],
            'recurring_full_day_weekly' => [
                'label' => 'Weekly Focus Day',
                'description' => 'Weekly full-day focus time',
                'startsOn' => new DateTime('2025-11-21 00:00:00', new DateTimeZone('UTC')),
                'endsOn' => new DateTime('2025-11-22 00:00:00', new DateTimeZone('UTC')),
                'timeless' => true,
                'participants' => null,
                'recurrence' => [
                    'precision' => 'weekly',
                    'interval' => 1,
                    'iterations' => 8,
                    'onDayOfWeek' => ['FR'],
                ],
            ],
            'recurring_full_day_monthly_absolute' => [
                'label' => 'Mid-Month Deadline',
                'description' => 'Monthly deadline on the 15th',
                'startsOn' => new DateTime('2025-12-15 00:00:00', new DateTimeZone('UTC')),
                'endsOn' => new DateTime('2025-12-16 00:00:00', new DateTimeZone('UTC')),
                'timeless' => true,
                'participants' => null,
                'recurrence' => [
                    'precision' => 'monthly',
                    'interval' => 1,
                    'iterations' => 6,
                    'onDayOfMonth' => [15],
                ],
            ],
            'recurring_full_day_monthly_relative' => [
                'label' => 'Monthly Training',
                'description' => 'Monthly training on the 2nd Monday',
                'startsOn' => new DateTime('2025-12-09 00:00:00', new DateTimeZone('UTC')),
                'endsOn' => new DateTime('2025-12-10 00:00:00', new DateTimeZone('UTC')),
                'timeless' => true,
                'participants' => null,
                'recurrence' => [
                    'precision' => 'monthly',
                    'interval' => 1,
                    'iterations' => 6,
                    'onDayOfWeek' => ['MO'],
                    'onPosition' => [2],
                ],
            ],
            'recurring_full_day_yearly_absolute' => [
                'label' => 'Annual Holiday',
                'description' => 'Annual holiday on June 10th',
                'startsOn' => new DateTime('2026-06-10 00:00:00', new DateTimeZone('UTC')),
                'endsOn' => new DateTime('2026-06-11 00:00:00', new DateTimeZone('UTC')),
                'timeless' => true,
                'participants' => null,
                'recurrence' => [
                    'precision' => 'yearly',
                    'interval' => 1,
                    'iterations' => 3,
                    'onMonthOfYear' => [6],
                ],
            ],
            'recurring_full_day_yearly_relative' => [
                'label' => 'Annual Meeting',
                'description' => 'Annual meeting on the 3rd Wednesday of May',
                'startsOn' => new DateTime('2026-05-20 00:00:00', new DateTimeZone('UTC')),
                'endsOn' => new DateTime('2026-05-21 00:00:00', new DateTimeZone('UTC')),
                'timeless' => true,
                'participants' => null,
                'recurrence' => [
                    'precision' => 'yearly',
                    'interval' => 1,
                    'iterations' => 3,
                    'onDayOfWeek' => ['WE'],
                    'onPosition' => [3],
                    'onMonthOfYear' => [5],
                ],
            ],
            default => throw new \InvalidArgumentException("Unknown test event key: $key"),
        };
    }

    public static function eventDataProvider(): array
    {
        return [
            'singleton_part_day_no_participants' => ['singleton_part_day_no_participants'],
            'singleton_full_day_no_participants' => ['singleton_full_day_no_participants'],
            'singleton_part_day_with_participants' => ['singleton_part_day_with_participants'],
            'singleton_full_day_with_participants' => ['singleton_full_day_with_participants'],
            'recurring_part_day_daily' => ['recurring_part_day_daily'],
            'recurring_part_day_weekly' => ['recurring_part_day_weekly'],
            'recurring_part_day_monthly_absolute' => ['recurring_part_day_monthly_absolute'],
            'recurring_part_day_monthly_relative' => ['recurring_part_day_monthly_relative'],
            'recurring_part_day_yearly_absolute' => ['recurring_part_day_yearly_absolute'],
            'recurring_part_day_yearly_relative' => ['recurring_part_day_yearly_relative'],
            'recurring_full_day_daily' => ['recurring_full_day_daily'],
            'recurring_full_day_weekly' => ['recurring_full_day_weekly'],
            'recurring_full_day_monthly_absolute' => ['recurring_full_day_monthly_absolute'],
            'recurring_full_day_monthly_relative' => ['recurring_full_day_monthly_relative'],
            'recurring_full_day_yearly_absolute' => ['recurring_full_day_yearly_absolute'],
            'recurring_full_day_yearly_relative' => ['recurring_full_day_yearly_relative'],
        ];
    }

    private function buildEventRequest(string $calendarId, array $data): array
    {
        $eventId = uniqid();
        $r = new EventSetRequest($this->account->id());
        $p = $r->create($eventId);

        $p->in($calendarId);
        $p->label($data['label']);
        $p->descriptionContents($data['description']);
        $p->starts($data['startsOn']);
        $p->duration($data['startsOn']->diff($data['endsOn']));
        $p->timeless($data['timeless']);

        if ($data['participants'] !== null) {
            $participant = $p->participants('participant-1');
            $participant->name('Test Participant');
            $participant->address('mailto:test@example.com');
            $participant->roles('attendee');
        }

        if ($data['recurrence'] !== null) {
            $rule = $p->recurrenceRule();
            $rule->frequency($data['recurrence']['precision']);
            $rule->interval($data['recurrence']['interval']);
            $rule->count($data['recurrence']['iterations']);

            if (isset($data['recurrence']['onDayOfWeek'])) {
                foreach ($data['recurrence']['onDayOfWeek'] as $day) {
                    $rule->byDayOfWeek()->day($day);
                }
            }
            if (isset($data['recurrence']['onDayOfMonth'])) {
                $rule->byDayOfMonth(...$data['recurrence']['onDayOfMonth']);
            }
            if (isset($data['recurrence']['onMonthOfYear'])) {
                $rule->byMonthOfYear(...$data['recurrence']['onMonthOfYear']);
            }
            if (isset($data['recurrence']['onPosition'])) {
                $rule->byPosition(...$data['recurrence']['onPosition']);
            }
        }

        return [$r, $eventId];
    }

    /**
     * @dataProvider eventDataProvider
     */
    public function testEventSetCreate(string $eventKey): void
    {
        $data = $this->generateEventData($eventKey);
        [$r0, $createId] = $this->buildEventRequest($this->calendarId, $data);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify create response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(EventSetResponse::class, $bundle->first());
        $result = $bundle->first()->createSuccess($createId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testEventSetUpdate(): void
    {
        // construct create request
        $data = $this->generateEventData('singleton_part_day_no_participants');
        [$r0, $createId] = $this->buildEventRequest($this->calendarId, $data);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract event id
        $result = $bundle->first()->createSuccess($createId);
        $eventId = $result['id'];

        // construct modify request
        $r1 = new EventSetRequest($this->account->id());
        $p1 = $r1->update($eventId);
        $p1->label('Updated Event ' . time());

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify modify response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(EventSetResponse::class, $bundle->first());
        $result = $bundle->first()->updateSuccess($eventId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testEventSetDelete(): void
    {
        // construct create request
        $data = $this->generateEventData('singleton_part_day_no_participants');
        [$r0, $createId] = $this->buildEventRequest($this->calendarId, $data);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract event id
        $result = $bundle->first()->createSuccess($createId);
        $eventId = $result['id'];

        // construct delete request
        $r1 = new EventSetRequest($this->account->id());
        $r1->delete($eventId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify delete response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(EventSetResponse::class, $bundle->first());
        $result = $bundle->first()->deleteSuccess($eventId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testEventGetSpecific(): void
    {
        // construct create request
        $data = $this->generateEventData('singleton_part_day_no_participants');
        [$r0, $createId] = $this->buildEventRequest($this->calendarId, $data);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract event id
        $result = $bundle->first()->createSuccess($createId);
        $eventId = $result['id'];

        // construct fetch request
        $r1 = new EventGetRequest($this->account->id());
        $r1->target($eventId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(EventGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(EventParameters::class, $result);
        $this->assertEquals($eventId, $result->id());
        $this->assertEquals($data['label'], $result->label());
    }

    public function testEventGetAll(): void
    {
        // construct create request
        $data = $this->generateEventData('singleton_part_day_no_participants');
        [$r0, $createId] = $this->buildEventRequest($this->calendarId, $data);

        // transceive
        $this->client->perform([$r0]);

        // construct fetch request
        $r1 = new EventGetRequest($this->account->id());

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(EventGetResponse::class, $bundle->first());
        $results = $bundle->first()->objects();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        foreach ($results as $result) {
            $this->assertInstanceOf(EventParameters::class, $result);
        }
    }

    public function testEventGetWithProperties(): void
    {
        // construct create request
        $data = $this->generateEventData('singleton_part_day_no_participants');
        [$r0, $createId] = $this->buildEventRequest($this->calendarId, $data);

        // transceive
        $this->client->perform([$r0]);

        // construct fetch request
        $r1 = new EventGetRequest($this->account->id());
        $r1->property('id', 'title', 'start');

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(EventGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(EventParameters::class, $result);
        $this->assertNotEmpty($result->id());
        $this->assertNotEmpty($result->label());
        $this->assertNotNull($result->starts());
        $this->assertNull($result->timezone());
    }

    public function testEventQueryWithoutFilter(): void
    {
        // construct create request
        $data = $this->generateEventData('singleton_part_day_no_participants');
        [$r0, $createId] = $this->buildEventRequest($this->calendarId, $data);

        // transceive
        $this->client->perform([$r0]);

        // construct query request
        $r1 = new EventQueryRequest($this->account->id());

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify query response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(EventQueryResponse::class, $bundle->first());
        $results = $bundle->first()->list();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
    }

    public function testEventQueryWithMatchingFilter(): void
    {
        // construct create request
        $data = $this->generateEventData('singleton_part_day_no_participants');
        [$r0, $createId] = $this->buildEventRequest($this->calendarId, $data);

        // transceive
        $this->client->perform([$r0]);

        // construct query request
        $r1 = new EventQueryRequest($this->account->id());
        $r1->filter()->in($this->calendarId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify query response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(EventQueryResponse::class, $bundle->first());
        $results = $bundle->first()->list();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
    }

    public function testEventQueryWithNonMatchingFilter(): void
    {
        // construct query request
        $r0 = new EventQueryRequest($this->account->id());
        $r0->filter()->title('This Event Does Not Exist ' . uniqid());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify query response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(EventQueryResponse::class, $bundle->first());
        $results = $bundle->first()->list();
        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    public function testEventQueryWithSorting(): void
    {
        // construct create requests for two events with different start times
        $data1 = $this->generateEventData('singleton_part_day_no_participants');
        [$r0, $id1] = $this->buildEventRequest($this->calendarId, $data1);

        $data2 = $this->generateEventData('singleton_full_day_no_participants');
        [$r1, $id2] = $this->buildEventRequest($this->calendarId, $data2);

        // transceive
        $this->client->perform([$r0, $r1]);

        // construct ascending sort query
        $rq0 = new EventQueryRequest($this->account->id());
        $rq0->filter()->in($this->calendarId);
        $rq0->sort()->start(true);

        // transceive ascending query
        $bundleAscending = $this->client->perform([$rq0]);

        // verify ascending fetch response
        $this->assertEquals(1, $bundleAscending->tally());
        $this->assertInstanceOf(EventQueryResponse::class, $bundleAscending->first());
        $ascendingResults = $bundleAscending->first()->list();
        $this->assertIsArray($ascendingResults);
        $this->assertNotEmpty($ascendingResults);

        // construct descending sort query
        $rq1 = new EventQueryRequest($this->account->id());
        $rq1->filter()->in($this->calendarId);
        $rq1->sort()->start(false);

        // transceive descending query
        $bundleDescending = $this->client->perform([$rq1]);

        // verify descending fetch response
        $this->assertEquals(1, $bundleDescending->tally());
        $this->assertInstanceOf(EventQueryResponse::class, $bundleDescending->first());
        $descendingResults = $bundleDescending->first()->list();
        $this->assertIsArray($descendingResults);
        $this->assertNotEmpty($descendingResults);
        $this->assertCount(count($ascendingResults), $descendingResults);

        $firstAscending = $ascendingResults[0];
        $lastDescending = $descendingResults[count($descendingResults) - 1];
        $this->assertSame($firstAscending, $lastDescending);
    }
}
