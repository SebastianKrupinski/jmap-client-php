<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses;

use JmapClient\Responses\Calendar\EventParameters;
use JmapClient\Responses\ResponseDate;
use PHPUnit\Framework\TestCase;

class ResponseDateTest extends TestCase
{
    public function testUtcParsesIntoUtc(): void
    {
        $date = ResponseDate::utc('2026-11-12T11:00:00Z');

        $this->assertSame('2026-11-12T11:00:00+00:00', $date->format(DATE_ATOM));
        $this->assertSame('UTC', $date->getTimezone()->getName());
    }

    public function testUtcNormalizesOffsetDates(): void
    {
        $date = ResponseDate::utc('2026-11-12T12:00:00+01:00');

        $this->assertSame('2026-11-12T11:00:00+00:00', $date->format(DATE_ATOM));
    }

    public function testLocalParsesInGivenTimeZone(): void
    {
        $date = ResponseDate::local('2026-12-01T10:00:00', 'America/Toronto');

        $this->assertSame('2026-12-01T10:00:00-05:00', $date->format(DATE_ATOM));
    }

    public function testLocalIgnoresInvalidTimeZone(): void
    {
        $date = ResponseDate::local('2026-12-01T10:00:00', 'Not/AZone');

        $this->assertSame('2026-12-01 10:00:00', $date->format('Y-m-d H:i:s'));
    }

    public function testOffsetKeepsOffset(): void
    {
        $date = ResponseDate::offset('2026-11-12T12:00:00+02:00');

        $this->assertSame('2026-11-12T12:00:00+02:00', $date->format(DATE_ATOM));
    }

    public function testMissingAndInvalidValuesReturnNull(): void
    {
        $this->assertNull(ResponseDate::utc(null));
        $this->assertNull(ResponseDate::utc(''));
        $this->assertNull(ResponseDate::utc('not a date'));
        $this->assertNull(ResponseDate::local(42));
        $this->assertNull(ResponseDate::offset([]));
    }

    public function testEventStartIsReadInTheEventTimeZone(): void
    {
        $event = new EventParameters([
            'start' => '2026-12-01T10:00:00',
            'timeZone' => 'Asia/Tokyo',
            'duration' => 'PT1H',
            'created' => '2026-11-12T11:00:00Z',
        ]);

        $this->assertSame('2026-12-01T10:00:00+09:00', $event->starts()->format(DATE_ATOM));
        $this->assertSame('2026-12-01T11:00:00+09:00', $event->ends()->format(DATE_ATOM));
        $this->assertSame('2026-11-12T11:00:00+00:00', $event->created()->format(DATE_ATOM));
    }
}
