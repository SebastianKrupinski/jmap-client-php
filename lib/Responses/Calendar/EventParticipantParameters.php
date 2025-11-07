<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Calendar;

use JmapClient\Responses\ResponseParameters;

class EventParticipantParameters extends ResponseParameters
{
    public function kind(): string|null
    {
        return $this->parameter('kind');
    }

    public function name(): string|null
    {
        return $this->parameter('name');
    }

    public function description(): string|null
    {
        return $this->parameter('description');
    }

    public function address(): string|null
    {
        return $this->parameter('email');
    }

    public function status(): string|null
    {
        return $this->parameter('participationStatus');
    }

    public function comment(): string|null
    {
        return $this->parameter('participationComment');
    }

    public function roles(): array
    {
        return $this->parameter('roles') ?? [];
    }
}
