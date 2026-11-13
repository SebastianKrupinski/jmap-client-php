<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Quota;

use JmapClient\Enums\Quota\QuotaResource;
use JmapClient\Enums\Quota\QuotaScope;
use JmapClient\Requests\RequestFilter;

class QuotaFilter extends RequestFilter
{
    public function name(string $value): static
    {
        $this->condition('name', $value);

        return $this;
    }

    public function scope(QuotaScope $value): static
    {
        $this->condition('scope', $value->value);

        return $this;
    }

    public function resource(QuotaResource $value): static
    {
        $this->condition('resourceType', $value->value);

        return $this;
    }

    public function type(string $value): static
    {
        $this->condition('type', $value);

        return $this;
    }
}
