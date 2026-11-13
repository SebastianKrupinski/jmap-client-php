<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Calendar;

use JmapClient\Enums\Calendar\EventParticipantKind;
use JmapClient\Enums\Calendar\EventParticipantRole;
use JmapClient\Enums\Calendar\EventParticipationStatus;
use JmapClient\Responses\ResponseParameters;

class EventParticipantParameters extends ResponseParameters
{
    public function kind(): EventParticipantKind|string|null
    {
        $value = $this->parameter('kind');
        return is_string($value) ? (EventParticipantKind::tryFrom($value) ?? $value) : null;
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

    public function calendarAddress(): string|null
    {
        return $this->parameter('calendarAddress');
    }

    public function status(): EventParticipationStatus|string|null
    {
        $value = $this->parameter('participationStatus');
        return is_string($value) ? (EventParticipationStatus::tryFrom($value) ?? $value) : null;
    }

    public function comment(): string|null
    {
        return $this->parameter('participationComment');
    }

    /**
     * @return array<int,EventParticipantRole|string> roles, a role this library does not know is returned as a string
     */
    public function roles(): array
    {
        $roles = array_keys(array_filter((array) ($this->parameter('roles') ?? [])));
        return array_map(fn ($role) => EventParticipantRole::tryFrom((string) $role) ?? (string) $role, $roles);
    }
}
