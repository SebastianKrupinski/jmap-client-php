<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Quota;

use JmapClient\Requests\RequestSort;

class QuotaSort extends RequestSort
{
    public function name(bool $value = true): static
    {
        $this->condition('name', $value);

        return $this;
    }

    public function used(bool $value = true): static
    {
        $this->condition('used', $value);

        return $this;
    }
}
