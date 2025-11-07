<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Calendar;

use JmapClient\Requests\RequestParameters;

class EventNotificationParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'Alert');
    }

    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    public function action(string $value): static
    {
        $this->parameter('action', $value);
        return $this;
    }

    public function trigger(string $value): EventNotificationTriggerAbsoluteParameters|EventNotificationTriggerRelativeParameters|EventNotificationTriggerUnknownParameters
    {
        if (!isset($this->_parameters->trigger)) {
            $this->parameter('trigger', new \stdClass());
        }
        return match ($value) {
            'absolute' => new EventNotificationTriggerAbsoluteParameters($this->_parameters->trigger),
            'offset' => new EventNotificationTriggerRelativeParameters($this->_parameters->trigger),
            default => new EventNotificationTriggerUnknownParameters($this->_parameters->trigger),
        };
    }

    public function acknowledged(string $value): static
    {
        $this->parameter('acknowledged', $value);
        return $this;
    }
}
