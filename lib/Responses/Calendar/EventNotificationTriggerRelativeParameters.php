<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Calendar;

use DateInterval;
use JmapClient\Responses\ResponseParameters;

class EventNotificationTriggerRelativeParameters extends ResponseParameters
{
    public function type(): string
    {
        return 'relative';
    }

    public function anchor(): string
    {
        return $this->parameter('relativeTo');
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
