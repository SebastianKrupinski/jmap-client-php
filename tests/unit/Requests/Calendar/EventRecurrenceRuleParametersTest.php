<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests\Calendar;

use JmapClient\Requests\Calendar\EventRecurrenceRuleParameters;
use PHPUnit\Framework\TestCase;

class EventRecurrenceRuleParametersTest extends TestCase
{
    public function testByMonthOfYearAsStrings(): void
    {
        $parameters = null;
        $rule = new EventRecurrenceRuleParameters($parameters);
        $rule->bind($parameters);

        $rule->byMonthOfYear(6, 12, '5L');

        $this->assertSame(['6', '12', '5L'], $parameters->byMonth);
    }
}
