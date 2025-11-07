<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestParameters;

class ContactOrganizationParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'Organization');
    }

    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    public function context(string ...$value): static
    {
        foreach ($value as $entry) {
            $collection[$entry] = true;
        }
        $this->parameter('contexts', (object)$collection);
        return $this;
    }

    public function name(string $value): static
    {
        $this->parameter('name', $value);
        return $this;
    }

    public function sorting(string $value): static
    {
        $this->parameter('sortAs', $value);
        return $this;
    }

    public function units(?int $id = null): ContactOrganizationUnitParameters
    {
        if (!isset($this->_parameters->units)) {
            $this->parameter('units', []);
        }
        if ($id) {
            if (!isset($this->_parameters->units[$id])) {
                $this->_parameters->units[$id] = new \stdClass();
            }
            return new ContactOrganizationUnitParameters($this->_parameters->units[$id]);
        } else {
            $this->_parameters->units[] = new \stdClass();
            return new ContactOrganizationUnitParameters(
                $this->_parameters->units[array_key_last($this->_parameters->units)]
            );
        }
    }
}
