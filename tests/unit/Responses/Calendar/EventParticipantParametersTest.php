<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Calendar;

use JmapClient\Responses\Calendar\EventParticipantParameters;
use PHPUnit\Framework\TestCase;

class EventParticipantParametersTest extends TestCase
{
    public function testCalendarAddress(): void
    {
        $participant = new EventParticipantParameters(['calendarAddress' => 'mailto:bob@example.com']);

        $this->assertSame('mailto:bob@example.com', $participant->calendarAddress());
    }

    public function testCalendarAddressMissing(): void
    {
        $this->assertNull((new EventParticipantParameters([]))->calendarAddress());
    }
}
