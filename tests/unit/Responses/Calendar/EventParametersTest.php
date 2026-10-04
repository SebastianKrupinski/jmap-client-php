<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Calendar;

use JmapClient\Responses\Calendar\EventParameters;
use PHPUnit\Framework\TestCase;

class EventParametersTest extends TestCase
{
    public function testIn(): void
    {
        $parameters = new EventParameters([
            'calendarIds' => ['calendar-1' => true, 'calendar-2' => true],
        ]);

        $this->assertSame(['calendar-1', 'calendar-2'], $parameters->in());
    }

    public function testInMissing(): void
    {
        $this->assertNull((new EventParameters([]))->in());
    }
}
