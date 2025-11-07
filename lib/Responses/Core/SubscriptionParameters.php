<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Core;

use JmapClient\Responses\ResponseParameters;

class SubscriptionParameters extends ResponseParameters
{
    public function id(): string|null
    {
        return $this->parameter('id');
    }

    public function device(): string|null
    {
        return $this->parameter('deviceClientId');
    }

    public function url(): string|null
    {
        return $this->parameter('url');
    }

    public function expiration(): string|null
    {
        return $this->parameter('expires');
    }

    public function verification(): string|null
    {
        return $this->parameter('verificationCode');
    }

    public function keys(): string|null
    {
        return $this->parameter('keys');
    }
}
