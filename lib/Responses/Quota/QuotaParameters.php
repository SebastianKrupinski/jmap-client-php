<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Quota;

use JmapClient\Enums\Quota\QuotaResource;
use JmapClient\Enums\Quota\QuotaScope;
use JmapClient\Responses\ResponseParameters;

class QuotaParameters extends ResponseParameters
{
    public function id(): string|null
    {
        return $this->parameter('id');
    }

    public function resource(): QuotaResource|null
    {
        $value = $this->parameter('resourceType');
        return is_string($value) ? QuotaResource::tryFrom($value) : null;
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

    public function scope(): QuotaScope|null
    {
        $value = $this->parameter('scope');
        return is_string($value) ? QuotaScope::tryFrom($value) : null;
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
