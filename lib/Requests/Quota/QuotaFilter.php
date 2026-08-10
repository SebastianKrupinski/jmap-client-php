<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Quota;

use JmapClient\Requests\RequestFilter;

class QuotaFilter extends RequestFilter
{
    public function name(string $value): static
    {
        $this->condition('name', $value);

        return $this;
    }

    public function scope(string $value): static
    {
        $this->condition('scope', $value);

        return $this;
    }

    public function resource(string $value): static
    {
        $this->condition('resourceType', $value);

        return $this;
    }

    public function type(string $value): static
    {
        $this->condition('type', $value);

        return $this;
    }
}
