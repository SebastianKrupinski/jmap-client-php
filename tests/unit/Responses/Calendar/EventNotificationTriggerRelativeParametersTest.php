<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Calendar;

use JmapClient\Responses\Calendar\EventNotificationTriggerRelativeParameters;
use PHPUnit\Framework\TestCase;

class EventNotificationTriggerRelativeParametersTest extends TestCase
{
    public function testAnchor(): void
    {
        $trigger = new EventNotificationTriggerRelativeParameters(['relativeTo' => 'end', 'offset' => 'PT5M']);

        $this->assertSame('end', $trigger->anchor());
    }

    public function testAnchorDefault(): void
    {
        $trigger = new EventNotificationTriggerRelativeParameters(['offset' => '-PT15M']);

        $this->assertSame('start', $trigger->anchor());
    }
}
