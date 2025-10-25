<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestParameters;

class ContactNameParameters extends RequestParameters {

    public function __construct(&$parameters = null) {
        parent::__construct($parameters);
        $this->parameter('@type', 'Name');
    }

    public function type(string $value): static {
        $this->parameter('@type', $value);
        return $this;
    }

    public function full(string $value): static {
        $this->parameter('full', $value);
        return $this;
    }

    public function components(?int $index = null): ContactComponentParameters {
        if (!isset($this->_parameters->components)) {
            $this->parameter('components', []);
        }
        if ($index) {
            if (!isset($this->_parameters->components[$index])){
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

    public function separator(string $value): static {
        $this->parameter('defaultSeparator', $value);
        return $this;
    }

    public function ordered(bool $value): static {
        $this->parameter('isOrdered', $value);
        return $this;
    }

    public function sorting(string $component, string $value): static {
        if (!isset($this->_parameters->sortAs?->$component)) {
            $this->parameterStructured('sortAs', $component, $value);
        }
        return $this;
    }

}
