<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Calendar;

use DateTimeImmutable;

class EventMutationParameters extends EventCommonParameters
{
    /* Scheduling Properties */

    public function mutationId(): DateTimeImmutable|null
    {
        $value = $this->parameter('recurrenceId');
        return ($value) ? new DateTimeImmutable($value) : null;
    }

    public function mutationTimeZone(): string|null
    {
        return $this->parameter('recurrenceIdTimeZone');
    }

}
