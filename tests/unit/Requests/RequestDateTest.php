<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use JmapClient\Requests\Calendar\EventSet;
use JmapClient\Requests\RequestBundle;
use JmapClient\Requests\RequestDate;
use PHPUnit\Framework\TestCase;

class RequestDateTest extends TestCase
{
    public function testUtcConvertsToUtc(): void
    {
        $date = new DateTimeImmutable('2026-11-12 12:00:00', new DateTimeZone('Europe/Warsaw'));

        $this->assertSame('2026-11-12T11:00:00Z', RequestDate::utc($date));
    }

    public function testUtcDoesNotModifyMutableDate(): void
    {
        $date = new DateTime('2026-11-12 12:00:00', new DateTimeZone('Europe/Warsaw'));
        RequestDate::utc($date);

        $this->assertSame('Europe/Warsaw', $date->getTimezone()->getName());
    }

    public function testLocalKeepsWallClockTime(): void
    {
        $date = new DateTimeImmutable('2026-11-12 12:00:00', new DateTimeZone('Europe/Warsaw'));

        $this->assertSame('2026-11-12T12:00:00', RequestDate::local($date));
    }

    public function testOffsetKeepsUtcOffset(): void
    {
        $date = new DateTimeImmutable('2026-11-12 12:00:00', new DateTimeZone('America/Toronto'));

        $this->assertSame('2026-11-12T12:00:00-05:00', RequestDate::offset($date));
    }

    public function testEventUsesLocalStartAndUtcTimestamps(): void
    {
        $zone = new DateTimeZone('Europe/Warsaw');
        $request = new EventSet('account-1', 'set-1');
        $request->create('e1')
            ->starts(new DateTimeImmutable('2026-12-01 10:00:00', $zone))
            ->created(new DateTimeImmutable('2026-11-12 12:00:00', $zone));

        $event = json_decode((new RequestBundle($request))->jsonEncode(), true)['methodCalls'][0][1]['create']['e1'];

        $this->assertSame('2026-12-01T10:00:00', $event['start']);
        $this->assertSame('2026-11-12T11:00:00Z', $event['created']);
    }
}
