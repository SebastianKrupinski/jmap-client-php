<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Calendar;

use JmapClient\Requests\RequestParameters;

class EventPhysicalLocationParameters extends RequestParameters {

    public function __construct(&$parameters = null) {
        parent::__construct($parameters);
        $this->parameter('@type', 'Location');
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

    public function timezone(string $value): self {
        $this->parameter('timeZone', $value);
        return $this;
    }

    public function coordinates(string $value): self {
        $this->parameter('coordinates', $value);
        return $this;
    }

    public function attributes(string ...$value): self {
        foreach ($value as $entry) {
            $collection[$entry] = true;
        }
        $this->parameter('locationTypes', (object)$collection);
        return $this;
    }

    public function links(array $value): self {
        // @todo Implement this method
        return $this;
    }

    public function relative(string $value): self {
        $this->parameter('relativeTo', $value);
        return $this;
    }

}
