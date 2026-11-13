<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Sieve;

use JmapClient\Requests\RequestFilter;

class ScriptFilter extends RequestFilter
{
    /**
     * Scripts whose name contains the value
     */
    public function label(string $value): static
    {
        $this->condition('name', $value);
        return $this;
    }

    public function active(bool $value): static
    {
        $this->condition('isActive', $value);
        return $this;
    }
}
