<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Calendar;

use JmapClient\Responses\Calendar\EventMutationParameters;
use PHPUnit\Framework\TestCase;

class EventMutationParametersTest extends TestCase
{
    public function testExcluded(): void
    {
        $this->assertTrue((new EventMutationParameters(['excluded' => true]))->excluded());
    }

    public function testNotExcluded(): void
    {
        $this->assertFalse((new EventMutationParameters(['excluded' => false]))->excluded());
        $this->assertFalse((new EventMutationParameters(['title' => 'Moved']))->excluded());
    }
}
