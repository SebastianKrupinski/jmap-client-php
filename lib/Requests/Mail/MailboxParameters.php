<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestParameters;

class MailboxParameters extends RequestParameters {

    public const PARAMETER_LIST = ['parentId', 'name', 'role', 'sortOrder', 'isSubscribed'];

    public function parametersRaw(array $value): self {
        $this->_parameters = (object) array_intersect_key($value, array_flip(self::PARAMETER_LIST));
        return $this;
    }

    public function in(string|null $value): self {
        $this->parameter('parentId', $value);
        return $this;
    }

    public function label(string $value): self {
        $this->parameter('name', $value);
        return $this;
    }

    public function role(string|null $value): self {
        $this->parameter('role', $value);
        return $this;
    }

    public function priority(int $value): self {
        $this->parameter('sortOrder', $value);
        return $this;
    }

    public function subscribed(bool $value): self {
        $this->parameter('isSubscribed', $value);
        return $this;
    }
    
}
