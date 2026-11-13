<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Contacts;

use JmapClient\Enums\Contacts\ContactContext;
use JmapClient\Enums\Contacts\ContactPhoneFeature;
use JmapClient\Requests\RequestParameters;

class ContactPhoneParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'Phone');
    }

    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    public function number(string $value): static
    {
        $this->parameter('number', $value);
        return $this;
    }

    public function context(ContactContext|string ...$value): static
    {
        foreach ($value as $entry) {
            $collection[$entry instanceof ContactContext ? $entry->value : $entry] = true;
        }
        $this->parameter('contexts', (object)$collection);
        return $this;
    }

    public function priority(int $value): static
    {
        $this->parameter('pref', $value);
        return $this;
    }

    public function label(string $value): static
    {
        $this->parameter('label', $value);
        return $this;
    }

    public function features(ContactPhoneFeature|string ...$value): static
    {
        foreach ($value as $entry) {
            $collection[$entry instanceof ContactPhoneFeature ? $entry->value : $entry] = true;
        }
        $this->parameter('features', (object)$collection);
        return $this;
    }
}
