<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Sieve;

use JmapClient\Responses\ResponseParameters;

class ScriptParameters extends ResponseParameters
{
    public function id(): string|null
    {
        return $this->parameter('id');
    }

    public function label(): string|null
    {
        return $this->parameter('name');
    }

    public function blob(): string|null
    {
        return $this->parameter('blobId');
    }

    public function active(): bool
    {
        return (bool) $this->parameter('isActive');
    }
}
