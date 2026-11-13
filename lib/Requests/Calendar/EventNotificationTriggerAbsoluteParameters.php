<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Calendar;

use DateTimeInterface;
use JmapClient\Requests\RequestDate;
use JmapClient\Requests\RequestParameters;

class EventNotificationTriggerAbsoluteParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'AbsoluteTrigger');
    }

    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    public function when(DateTimeInterface $value): static
    {
        $this->parameter('when', RequestDate::utc($value));
        return $this;
    }
}
