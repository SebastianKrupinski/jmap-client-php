<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Calendar;

use JmapClient\Requests\RequestParameters;

class EventVirtualLocationParameters extends RequestParameters {

    public function __construct(&$parameters = null) {
        parent::__construct($parameters);
        $this->parameter('@type', 'VirtualLocation');
    }

    public function type(string $value): static {
        $this->parameter('@type', $value);
        return $this;
    }

    public function label(string $value): self {
        $this->parameter('name', $value);
        return $this;
    }

    public function description(string $value): self {
        $this->parameter('description', $value);
        return $this;
    }

    public function location(string $value): self {
        $this->parameter('uri', $value);
        return $this;
    }

    public function attributes(string ...$value): self {
        foreach ($value as $entry) {
            $collection[$entry] = true;
        }
        $this->parameter('features', (object)$collection);
        return $this;
    }

}
