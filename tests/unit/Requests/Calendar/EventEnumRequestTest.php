<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests\Calendar;

use JmapClient\Enums\Calendar\EventAlertAction;
use JmapClient\Enums\Calendar\EventAvailability;
use JmapClient\Enums\Calendar\EventFrequency;
use JmapClient\Enums\Calendar\EventMethod;
use JmapClient\Enums\Calendar\EventParticipantKind;
use JmapClient\Enums\Calendar\EventParticipantRole;
use JmapClient\Enums\Calendar\EventParticipationStatus;
use JmapClient\Enums\Calendar\EventPrivacy;
use JmapClient\Enums\Calendar\EventRelativeTo;
use JmapClient\Enums\Calendar\EventSendProtocol;
use JmapClient\Enums\Calendar\EventVirtualFeature;
use JmapClient\Enums\Calendar\EventWeekday;
use JmapClient\Requests\Calendar\EventQuery;
use JmapClient\Requests\Calendar\EventSet;
use JmapClient\Requests\RequestBundle;
use PHPUnit\Framework\TestCase;

class EventEnumRequestTest extends TestCase
{
    private function event(EventSet $request): array
    {
        return json_decode((new RequestBundle($request))->jsonEncode(), true, 512, JSON_THROW_ON_ERROR)['methodCalls'][0][1]['create']['e1'];
    }

    public function testEventPropertiesAcceptEnums(): void
    {
        $request = new EventSet('account-1');
        $event = $request->create('e1');
        $event->method(EventMethod::Request)->privacy(EventPrivacy::Private)->availability(EventAvailability::Free);

        $data = $this->event($request);

        $this->assertSame('request', $data['method']);
        $this->assertSame('private', $data['privacy']);
        $this->assertSame('free', $data['freeBusyStatus']);
    }

    public function testOpenValuesAcceptCustomStrings(): void
    {
        $request = new EventSet('account-1');
        $request->create('e1')->privacy('x-confidential')->availability('x-tentative');

        $data = $this->event($request);

        $this->assertSame('x-confidential', $data['privacy']);
        $this->assertSame('x-tentative', $data['freeBusyStatus']);
    }

    public function testParticipantAcceptsEnumsAndCustomStrings(): void
    {
        $request = new EventSet('account-1');
        $request->create('e1')->participants('p1')
            ->kind(EventParticipantKind::Individual)
            ->status(EventParticipationStatus::NeedsAction)
            ->roles(EventParticipantRole::Attendee, EventParticipantRole::Chair, 'x-scribe')
            ->send(EventSendProtocol::Imip, 'mailto:one@example.com');

        $participant = $this->event($request)['participants']['p1'];

        $this->assertSame('individual', $participant['kind']);
        $this->assertSame('needs-action', $participant['participationStatus']);
        $this->assertSame(['attendee' => true, 'chair' => true, 'x-scribe' => true], $participant['roles']);
        $this->assertSame(['imip' => 'mailto:one@example.com'], $participant['sendTo']);
    }

    public function testRecurrenceUsesLowercaseValues(): void
    {
        $request = new EventSet('account-1');
        $rule = $request->create('e1')->recurrenceRule();
        $rule->frequency(EventFrequency::Weekly);
        $rule->byDayOfWeek()->day(EventWeekday::Monday);
        $rule->byDayOfWeek()->day(EventWeekday::Friday);

        $data = $this->event($request)['recurrenceRule'];

        $this->assertSame('weekly', $data['frequency']);
        $this->assertSame(['mo', 'fr'], array_column($data['byDay'], 'day'));
    }

    public function testAlertLocationAndFilterAcceptEnums(): void
    {
        $request = new EventSet('account-1');
        $event = $request->create('e1');
        $alert = $event->notifications('a1');
        $alert->action(EventAlertAction::Display);
        $alert->trigger('offset')->anchor(EventRelativeTo::End);
        $event->physicalLocations('l1')->relative(EventRelativeTo::Start);
        $event->virtualLocations('v1')->attributes(EventVirtualFeature::Video, 'x-whiteboard');
        $query = new EventQuery('account-1');
        $query->filter()->participation(EventParticipationStatus::Accepted);

        $data = $this->event($request);
        $filter = json_decode((new RequestBundle($query))->jsonEncode(), true)['methodCalls'][0][1]['filter'];

        $this->assertSame('display', $data['alerts']['a1']['action']);
        $this->assertSame('end', $data['alerts']['a1']['trigger']['relativeTo']);
        $this->assertSame('start', $data['locations']['l1']['relativeTo']);
        $this->assertSame(['video' => true, 'x-whiteboard' => true], $data['locations']['v1']['features']);
        $this->assertSame(['participationStatus' => 'accepted'], $filter);
    }
}
