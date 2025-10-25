<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestFilter;

class MailboxFilter extends RequestFilter {

    public function in(string $value): self {
        $this->condition('parentId', $value);
        return $this;
    }

    public function name(string $value): self {
        $this->condition('name', $value);
        return $this;
    }

    public function role(string $value): self {
        $this->condition('role', $value);
        return $this;
    }

    public function hasRoles(bool $value): self {
        $this->condition('hasAnyRole', $value);
        return $this;
    }

    public function isSubscribed(bool $value): self {
        $this->condition('isSubscribed', $value);
        return $this;
    }

}
