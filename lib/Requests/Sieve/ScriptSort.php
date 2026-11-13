<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Sieve;

use JmapClient\Requests\RequestSort;

class ScriptSort extends RequestSort
{
    public function label(bool $value = true): static
    {
        $this->condition('name', $value);
        return $this;
    }

    public function active(bool $value = true): static
    {
        $this->condition('isActive', $value);
        return $this;
    }
}
