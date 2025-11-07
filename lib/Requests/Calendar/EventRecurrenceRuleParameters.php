<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Calendar;

use DateTime;
use DateTimeImmutable;
use JmapClient\Requests\RequestParameters;

class EventRecurrenceRuleParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'RecurrenceRule');
    }

    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    public function frequency(string $value): static
    {
        $this->parameter('frequency', $value);
        return $this;
    }

    public function interval(int $value): static
    {
        $this->parameter('interval', $value);
        return $this;
    }

    public function count(int $value): static
    {
        $this->parameter('count', $value);
        return $this;
    }

    public function until(DateTime|DateTimeImmutable $value): static
    {
        $this->parameter('until', $value->format(self::DATE_FORMAT_LOCAL));
        return $this;
    }

    public function scale(string $value): static
    {
        $this->parameter('rscale', $value);
        return $this;
    }

    public function byDayOfWeek(?int $id = null): EventRecurrenceDayParameters
    {
        if (!isset($this->_parameters->byDay)) {
            $this->_parameters->byDay = [];
        }
        if ($id) {
            if (!isset($this->_parameters->byDay[$id])) {
                $this->_parameters->byDay[$id] = new \stdClass();
            }
            return new EventRecurrenceDayParameters($this->_parameters->byDay[$id]);
        } else {
            $this->_parameters->byDay[] = new \stdClass();
            return new EventRecurrenceDayParameters(
                $this->_parameters->byDay[array_key_last($this->_parameters->byDay)]
            );
        }
    }

    public function byDayOfMonth(int ...$value): static
    {
        $this->parameter('byMonthDay', $value);
        return $this;
    }

    public function byDayOfYear(int ...$value): static
    {
        $this->parameter('byYearDay', $value);
        return $this;
    }

    public function byWeekOfYear(int ...$value): static
    {
        $this->parameter('byWeekNo', $value);
        return $this;
    }

    public function byMonthOfYear(int ...$value): static
    {
        $this->parameter('byMonth', $value);
        return $this;
    }

    public function byHour(int ...$value): static
    {
        $this->parameter('byHour', $value);
        return $this;
    }

    public function byMinute(int ...$value): static
    {
        $this->parameter('byMinute', $value);
        return $this;
    }

    public function bySecond(int ...$value): static
    {
        $this->parameter('bySecond', $value);
        return $this;
    }

    public function byPosition(int ...$value): static
    {
        $this->parameter('bySetPosition', $value);
        return $this;
    }
}
