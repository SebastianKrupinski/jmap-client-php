<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Core;

use DateTimeInterface;
use JmapClient\Requests\RequestParameters;

class SubscriptionParameters extends RequestParameters
{
    public function id(string $value): static
    {
        $this->parameter('id', $value);
        return $this;
    }

    public function device(string $value): static
    {
        $this->parameter('deviceClientId', $value);
        return $this;
    }

    public function url(string $value): static
    {
        $this->parameter('url', $value);
        return $this;
    }

    public function expiration(DateTimeInterface $value): static
    {
        $this->parameter('expires', $value->format(self::DATE_FORMAT_UTC));
        return $this;
    }

    public function verification(string $value): static
    {
        $this->parameter('verificationCode', $value);
        return $this;
    }

    public function keys(string $value): static
    {
        $this->parameter('keys', $value);
        return $this;
    }
}
