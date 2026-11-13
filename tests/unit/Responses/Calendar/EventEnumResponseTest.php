<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Calendar;

use JmapClient\Enums\Calendar\EventAlertAction;
use JmapClient\Enums\Calendar\EventAvailability;
use JmapClient\Enums\Calendar\EventFrequency;
use JmapClient\Enums\Calendar\EventMethod;
use JmapClient\Enums\Calendar\EventParticipantKind;
use JmapClient\Enums\Calendar\EventParticipantRole;
use JmapClient\Enums\Calendar\EventParticipationStatus;
use JmapClient\Enums\Calendar\EventPrivacy;
use JmapClient\Enums\Calendar\EventRelativeTo;
use JmapClient\Enums\Calendar\EventVirtualFeature;
use JmapClient\Responses\Calendar\EventNotificationParameters;
use JmapClient\Responses\Calendar\EventParameters;
use JmapClient\Responses\Calendar\EventParticipantParameters;
use JmapClient\Responses\Calendar\EventPhysicalLocationParameters;
use JmapClient\Responses\Calendar\EventRecurrenceRuleParameters;
use JmapClient\Responses\Calendar\EventVirtualLocationParameters;
use PHPUnit\Framework\TestCase;

class EventEnumResponseTest extends TestCase
{
    public function testEventPropertiesReturnEnums(): void
    {
        $event = new EventParameters(['method' => 'reply', 'privacy' => 'secret', 'freeBusyStatus' => 'busy']);

        $this->assertSame(EventMethod::Reply, $event->method());
        $this->assertSame(EventPrivacy::Secret, $event->privacy());
        $this->assertSame(EventAvailability::Busy, $event->availability());
    }

    public function testOpenValuesReturnCustomStringsAndClosedValuesReturnNull(): void
    {
        $event = new EventParameters(['method' => 'x-unknown', 'privacy' => 'x-confidential', 'freeBusyStatus' => null]);

        $this->assertNull($event->method());
        $this->assertSame('x-confidential', $event->privacy());
        $this->assertNull($event->availability());
    }

    public function testParticipantReturnsEnumsAndRoleList(): void
    {
        $participant = new EventParticipantParameters([
            'kind' => 'resource',
            'participationStatus' => 'tentative',
            'roles' => ['attendee' => true, 'x-scribe' => true, 'chair' => false],
        ]);

        $this->assertSame(EventParticipantKind::Resource, $participant->kind());
        $this->assertSame(EventParticipationStatus::Tentative, $participant->status());
        $this->assertSame([EventParticipantRole::Attendee, 'x-scribe'], $participant->roles());
        $this->assertSame([], (new EventParticipantParameters([]))->roles());
    }

    public function testAlertLocationAndRecurrenceReturnEnums(): void
    {
        $this->assertSame(EventAlertAction::Email, (new EventNotificationParameters(['action' => 'email']))->action());
        $this->assertSame(EventRelativeTo::End, (new EventPhysicalLocationParameters(['relativeTo' => 'end']))->relative());
        $this->assertSame('x-venue', (new EventPhysicalLocationParameters(['relativeTo' => 'x-venue']))->relative());
        $this->assertSame(EventFrequency::Monthly, (new EventRecurrenceRuleParameters(['frequency' => 'monthly']))->frequency());
        $this->assertNull((new EventRecurrenceRuleParameters(['frequency' => 'fortnightly']))->frequency());
    }

    public function testVirtualLocationFeatures(): void
    {
        $location = new EventVirtualLocationParameters(['features' => ['video' => true, 'x-whiteboard' => true, 'chat' => false]]);

        $this->assertSame([EventVirtualFeature::Video, 'x-whiteboard'], $location->attributes());
        $this->assertNull((new EventVirtualLocationParameters([]))->attributes());
    }
}
