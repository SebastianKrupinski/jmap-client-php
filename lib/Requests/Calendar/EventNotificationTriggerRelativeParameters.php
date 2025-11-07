<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Calendar;

use DateInterval;
use JmapClient\Requests\RequestParameters;

class EventNotificationTriggerRelativeParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'OffsetTrigger');
    }

    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    public function anchor(string $value): static
    {
        $this->parameter('relativeTo', $value);
        return $this;
    }

    public function offset(DateInterval $value): static
    {
        $this->parameter('offset', match (true) {
            ($value->y > 0) => $value->format('%rP%yY%mM%dDT%hH%iM'),
            ($value->m > 0) => $value->format('%rP%mM%dDT%hH%iM'),
            ($value->d > 0) => $value->format('%rP%dDT%hH%iM'),
            ($value->h > 0) => $value->format('%rPT%hH%iM'),
            default => $value->format('%rPT%iM')
        });
        return $this;
    }
}
