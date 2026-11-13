<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Contacts;

use JmapClient\Enums\Contacts\ContactAddressComponentKind;
use JmapClient\Enums\Contacts\ContactNameComponentKind;
use JmapClient\Requests\RequestParameters;

class ContactComponentParameters extends RequestParameters
{
    public function kind(ContactNameComponentKind|ContactAddressComponentKind|string $value): static
    {
        $this->parameter('kind', $value instanceof \BackedEnum ? $value->value : $value);
        return $this;
    }

    public function value(string $value): static
    {
        $this->parameter('value', $value);
        return $this;
    }
}
