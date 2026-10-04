<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests\Calendar;

use JmapClient\Requests\Calendar\EventParticipantParameters;
use PHPUnit\Framework\TestCase;

class EventParticipantParametersTest extends TestCase
{
    public function testCalendarAddress(): void
    {
        $parameters = null;
        $participant = new EventParticipantParameters($parameters);
        $participant->bind($parameters);

        $participant->calendarAddress('mailto:bob@example.com');

        $this->assertSame('mailto:bob@example.com', $parameters->calendarAddress);
    }
}
