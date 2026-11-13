<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Calendar;

use DateInterval;
use JmapClient\Enums\Calendar\EventRelativeTo;
use JmapClient\Responses\ResponseParameters;

class EventNotificationTriggerRelativeParameters extends ResponseParameters
{
    public function type(): string
    {
        return 'relative';
    }

    /**
     * start or end, "start" when omitted (RFC 8984 4.5.2)
     */
    public function anchor(): EventRelativeTo
    {
        $value = $this->parameter('relativeTo');
        return (is_string($value) ? EventRelativeTo::tryFrom($value) : null) ?? EventRelativeTo::Start;
    }

    public function offset(): DateInterval
    {
        $value = $this->parameter('offset');
        if (strpos($value, '-') === 0) {
            $value = new DateInterval(ltrim($value, '-'));
            $value->invert = 1;
            return $value;
        } else {
            return new DateInterval($value);
        }
    }
}
