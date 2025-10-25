<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Calendar;

use JmapClient\Requests\RequestParameters;

class EventParticipantParameters extends RequestParameters {

    public function __construct(&$parameters = null) {
        parent::__construct($parameters);
        $this->parameter('@type', 'Participant');
    }
    
    public function type(string $value): static {
        $this->parameter('@type', $value);
        return $this;
    }

    public function kind(string $value): self {
        $this->parameter('kind', $value);
        return $this;
    }

    public function name(string $value): self {
        $this->parameter('name', $value);
        return $this;
    }

    public function description(string $value): self {
        $this->parameter('description', $value);
        return $this;
    }

    public function address(string $value): self {
        $this->parameter('email', $value);
        return $this;
    }

    public function send(string $protocol, string $value): self {
        $this->parameterStructured('sendTo', $protocol, $value);
        return $this;
    }

    public function status(string $value): self {
        $this->parameter('participationStatus', $value);
        return $this;
    }

    public function comment(string $value): self {
        $this->parameter('participationComment', $value);
        return $this;
    }

    public function roles(string ...$value): self {
        foreach ($value as $entry) {
            $collection[$entry] = true;
        }
        $this->parameter('roles', (object)$collection);
        return $this;
    }

}
