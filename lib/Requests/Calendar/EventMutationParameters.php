<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Calendar;

use DateTimeInterface;

class EventMutationParameters extends EventCommonParameters
{
    public function mutationId(DateTimeInterface $value): static
    {
        $this->parameter('recurrenceId', $value->format(self::DATE_FORMAT_LOCAL));
        return $this;
    }

    public function mutationTimeZone(string $value): static
    {
        $this->parameter('recurrenceIdTimeZone', $value);
        return $this;
    }
}
