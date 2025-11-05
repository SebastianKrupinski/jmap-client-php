<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Contacts;

use DateTimeZone;
use JmapClient\Requests\RequestParameters;

class ContactAddressParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'Address');
    }

    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    public function full(string $value): static
    {
        $this->parameter('full', $value);
        return $this;
    }

    public function components(?int $index = null): ContactComponentParameters
    {
        if (!isset($this->_parameters->components)) {
            $this->parameter('components', []);
        }
        if ($index) {
            if (!isset($this->_parameters->components[$index])) {
                $this->_parameters->components[$index] = new \stdClass();
            }
            return new ContactComponentParameters($this->_parameters->components[$index]);
        } else {
            $this->_parameters->components[] = new \stdClass();
            return new ContactComponentParameters(
                $this->_parameters->components[array_key_last($this->_parameters->components)]
            );
        }
    }

    public function separator(string $value): static
    {
        $this->parameter('defaultSeparator', $value);
        return $this;
    }

    public function ordered(bool $value): static
    {
        $this->parameter('isOrdered', $value);
        return $this;
    }

    public function country(string $value): static
    {
        $this->parameter('countryCode', $value);
        return $this;
    }

    public function coordinates(float $latitude, float $longitude): static
    {
        $this->parameter('coordinates', "geo:$latitude,$longitude");
        return $this;
    }

    public function timeZone(DateTimeZone $value): static
    {
        $this->parameter('timeZone', $value->getName());
        return $this;
    }

    public function phoneticScript(string $value): static
    {
        $this->parameter('phoneticScript', $value);
        return $this;
    }

    public function phoneticSystem(string $value): static
    {
        $this->parameter('phoneticSystem', $value);
        return $this;
    }

}
