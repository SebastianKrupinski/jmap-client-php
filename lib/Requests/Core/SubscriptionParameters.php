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

    public function id(string $value): self {
        $this->parameter('id', $value);
        return $this;
    }

    public function device(string $value): self {
        $this->parameter('deviceClientId', $value);
        return $this;
    }
    
    public function url(string $value): self {
        $this->parameter('url', $value);
        return $this;
    }
    
    public function expiration(DateTimeInterface $value): self {
        $this->parameter('expires', $value->format(self::DATE_FORMAT_UTC));
        return $this;
    }

    public function verification(string $value): self {
        $this->parameter('verificationCode', $value);
        return $this;
    }

    public function keys(string $value): self {
        $this->parameter('keys', $value);
        return $this;
    }

}
