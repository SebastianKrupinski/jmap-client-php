<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Calendar;

use JmapClient\Enums\Calendar\EventParticipantKind;
use JmapClient\Enums\Calendar\EventParticipantRole;
use JmapClient\Enums\Calendar\EventParticipationStatus;
use JmapClient\Enums\Calendar\EventSendProtocol;
use JmapClient\Requests\RequestParameters;

class EventParticipantParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'Participant');
    }

    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    public function kind(EventParticipantKind|string $value): static
    {
        $this->parameter('kind', $value instanceof EventParticipantKind ? $value->value : $value);
        return $this;
    }

    public function name(string $value): static
    {
        $this->parameter('name', $value);
        return $this;
    }

    public function description(string $value): static
    {
        $this->parameter('description', $value);
        return $this;
    }

    public function address(string $value): static
    {
        $this->parameter('email', $value);
        return $this;
    }

    public function calendarAddress(string $value): static
    {
        $this->parameter('calendarAddress', $value);
        return $this;
    }

    public function send(EventSendProtocol|string $protocol, string $value): static
    {
        $this->parameterStructured('sendTo', $protocol instanceof EventSendProtocol ? $protocol->value : $protocol, $value);
        return $this;
    }

    public function status(EventParticipationStatus|string $value): static
    {
        $this->parameter('participationStatus', $value instanceof EventParticipationStatus ? $value->value : $value);
        return $this;
    }

    public function comment(string $value): static
    {
        $this->parameter('participationComment', $value);
        return $this;
    }

    public function roles(EventParticipantRole|string ...$values): static
    {
        if ($values === []) {
            return $this;
        }
        foreach ($values as $value) {
            $collection[$value instanceof EventParticipantRole ? $value->value : $value] = true;
        }
        $this->parameter('roles', (object)$collection);
        return $this;
    }
}
