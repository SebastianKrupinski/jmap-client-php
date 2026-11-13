<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Calendar;

use JmapClient\Enums\Calendar\EventWeekday;
use JmapClient\Requests\RequestParameters;

class EventRecurrenceDayParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'NDay');
    }

    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    public function day(EventWeekday $value): static
    {
        $this->parameter('day', $value->value);
        return $this;
    }

    public function ordinal(int $value): static
    {
        $this->parameter('nthOfPeriod', $value);
        return $this;
    }
}
