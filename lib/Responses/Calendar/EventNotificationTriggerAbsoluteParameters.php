<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Calendar;

use DateTimeImmutable;
use JmapClient\Responses\ResponseParameters;

class EventNotificationTriggerAbsoluteParameters extends ResponseParameters
{
    public function type(): string
    {
        return 'absolute';
    }

    public function when(): DateTimeImmutable|null
    {
        $value = $this->parameter('when');
        return ($value) ? new DateTimeImmutable($value) : null;
    }

}
