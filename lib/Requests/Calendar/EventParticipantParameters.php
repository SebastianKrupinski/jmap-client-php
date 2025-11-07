<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Calendar;

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

    public function kind(string $value): static
    {
        $this->parameter('kind', $value);
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

    public function send(string $protocol, string $value): static
    {
        $this->parameterStructured('sendTo', $protocol, $value);
        return $this;
    }

    public function status(string $value): static
    {
        $this->parameter('participationStatus', $value);
        return $this;
    }

    public function comment(string $value): static
    {
        $this->parameter('participationComment', $value);
        return $this;
    }

    public function roles(string ...$values): static
    {
        if ($values === []) {
            return $this;
        }
        foreach ($values as $value) {
            $collection[$value] = true;
        }
        $this->parameter('roles', (object)$collection);
        return $this;
    }
}
