<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Contacts;

use JmapClient\Enums\Contacts\ContactAddressComponentKind;
use JmapClient\Enums\Contacts\ContactNameComponentKind;
use JmapClient\Responses\ResponseParameters;

class ContactComponentParameters extends ResponseParameters
{
    public function kind(): ContactNameComponentKind|ContactAddressComponentKind|string|null
    {
        $value = $this->parameter('kind');
        return is_string($value) ? (ContactNameComponentKind::tryFrom($value) ?? ContactAddressComponentKind::tryFrom($value) ?? $value) : null;
    }

    public function value(): string|null
    {
        return $this->parameter('value');
    }
}
