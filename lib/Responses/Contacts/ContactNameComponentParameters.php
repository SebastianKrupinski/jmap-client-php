<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Contacts;

use JmapClient\Enums\Contacts\ContactNameComponentKind;

class ContactNameComponentParameters extends ContactComponentParameters
{
    public function kind(): ContactNameComponentKind|string|null
    {
        $value = $this->parameter('kind');
        return is_string($value) ? (ContactNameComponentKind::tryFrom($value) ?? $value) : null;
    }
}
