<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Calendar;

use JmapClient\Responses\ResponseParameters;

class EventNotificationParameters extends ResponseParameters
{
    public function type(): string|null
    {
        return $this->parameter('@type');
    }

    public function action(): string|null
    {
        return $this->parameter('action');
    }

    public function trigger(): mixed
    {
        $trigger = $this->parameter('trigger') ?? [];
        return match ($trigger['@type']) {
            'AbsoluteTrigger' => new EventNotificationTriggerAbsoluteParameters($trigger),
            'OffsetTrigger' => new EventNotificationTriggerRelativeParameters($trigger),
            'UnknownTrigger' => new EventNotificationTriggerRelativeParameters($trigger),
        };
    }

    public function acknowledged(): string|null
    {
        return $this->parameter('acknowledged');
    }
}
