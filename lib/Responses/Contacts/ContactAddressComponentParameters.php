<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Contacts;

use JmapClient\Enums\Contacts\ContactAddressComponentKind;

class ContactAddressComponentParameters extends ContactComponentParameters
{
    public function kind(): ContactAddressComponentKind|string|null
    {
        $value = $this->parameter('kind');
        return is_string($value) ? (ContactAddressComponentKind::tryFrom($value) ?? $value) : null;
    }
}
