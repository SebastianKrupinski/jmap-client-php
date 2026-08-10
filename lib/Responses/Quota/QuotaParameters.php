<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Quota;

use JmapClient\Responses\ResponseParameters;

class QuotaParameters extends ResponseParameters
{
    public function id(): string|null
    {
        return $this->parameter('id');
    }

    public function resource(): string|null
    {
        return $this->parameter('resourceType');
    }

    public function used(): int|null
    {
        return $this->parameter('used');
    }

    public function warnLimit(): int|null
    {
        return $this->parameter('warnLimit');
    }

    public function softLimit(): int|null
    {
        return $this->parameter('softLimit');
    }

    public function hardLimit(): int|null
    {
        return $this->parameter('hardLimit');
    }

    public function scope(): string|null
    {
        return $this->parameter('scope');
    }

    public function name(): string|null
    {
        return $this->parameter('name');
    }

    public function types(): array|null
    {
        return $this->parameter('types');
    }

    public function description(): string|null
    {
        return $this->parameter('description');
    }
}
