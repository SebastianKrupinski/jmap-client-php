<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Calendar;

use JmapClient\Responses\ResponseParameters;

class CalendarParameters extends ResponseParameters
{
    public function id(): string|null
    {
        return $this->parameter('id');
    }

    public function label(): string|null
    {
        return $this->parameter('name');
    }

    public function description(): string|null
    {
        return $this->parameter('description');
    }

    public function color(): string|null
    {
        return $this->parameter('color');
    }

    public function priority(): int|null
    {
        return $this->parameter('sortOrder');
    }

    public function subscribed(): bool|null
    {
        return $this->parameter('isSubscribed');
    }

    public function visible(): bool|null
    {
        return $this->parameter('isVisible');
    }

    public function default(): bool|null
    {
        return $this->parameter('isDefault');
    }

    public function timezone(): string|null
    {
        return $this->parameter('timeZone');
    }

    public function sharees(): array|null
    {
        return $this->parameter('shareWith');
    }

    public function rights(): CalendarPermissions|null
    {
        $rights = $this->parameter('myRights');
        if ($rights === null) {
            return null;
        }
        return new CalendarPermissions((array) $rights);
    }
}
