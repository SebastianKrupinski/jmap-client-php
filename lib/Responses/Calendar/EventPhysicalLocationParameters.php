<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Calendar;

use JmapClient\Responses\ResponseParameters;

class EventPhysicalLocationParameters extends ResponseParameters
{
    public function label(): string|null
    {
        return $this->parameter('name');
    }

    public function description(): string|null
    {
        return $this->parameter('description');
    }

    public function timezone(): string|null
    {
        return $this->parameter('timeZone');
    }

    public function coordinates(): string|null
    {
        return $this->parameter('coordinates');
    }

    public function attributes(): array
    {
        return $this->parameter('locationTypes');
    }

    public function links(): array
    {
        return $this->parameter('links');
    }

    public function relative(): string|null
    {
        return $this->parameter('relativeTo');
    }

}
