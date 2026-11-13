<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Mail;

use JmapClient\Enums\Mail\MailboxRole;
use JmapClient\Requests\RequestFilter;

class MailboxFilter extends RequestFilter
{
    public function in(string $value): static
    {
        $this->condition('parentId', $value);
        return $this;
    }

    public function name(string $value): static
    {
        $this->condition('name', $value);
        return $this;
    }

    public function role(MailboxRole|string $value): static
    {
        $this->condition('role', $value instanceof MailboxRole ? $value->value : $value);
        return $this;
    }

    public function hasRoles(bool $value): static
    {
        $this->condition('hasAnyRole', $value);
        return $this;
    }

    public function isSubscribed(bool $value): static
    {
        $this->condition('isSubscribed', $value);
        return $this;
    }
}
