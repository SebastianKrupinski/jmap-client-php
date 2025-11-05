<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Calendar;

use JmapClient\Responses\ResponseParameters;

class EventRecurrenceRuleParameters extends ResponseParameters
{
    public function frequency(): string|null
    {
        return $this->parameter('frequency');
    }

    public function interval(): int
    {
        return $this->parameter('interval') ?? 1;
    }

    public function count(): int|null
    {
        return $this->parameter('count');
    }

    public function until(): string|null
    {
        return $this->parameter('until');
    }

    public function scale(): string
    {
        return $this->parameter('rscale') ?? 'gregorian';
    }

    public function byDayOfWeek(): array
    {
        return $this->parameter('byDay') ?? [];
    }

    public function byDayOfMonth(): array
    {
        return $this->parameter('byMonthDay') ?? [];
    }

    public function byDayOfYear(): array
    {
        return $this->parameter('byYearDay') ?? [];
    }

    public function byWeekOfYear(): array
    {
        return $this->parameter('byWeekNo') ?? [];
    }

    public function byMonthOfYear(): array
    {
        return $this->parameter('byMonth') ?? [];
    }

    public function byHour(): array
    {
        return $this->parameter('byHour') ?? [];
    }

    public function byMinute(): array
    {
        return $this->parameter('byMinute') ?? [];
    }

    public function bySecond(): array
    {
        return $this->parameter('bySecond') ?? [];
    }

    public function byPosition(): array
    {
        return $this->parameter('bySetPosition') ?? [];
    }

}
