<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Files;

use JmapClient\Requests\RequestSort;

class NodeSort extends RequestSort
{
    public function type(bool $value = true): static
    {
        $this->condition('type', $value, null, null);
        return $this;
    }

    public function hasType(bool $value = true): static
    {
        $this->condition('hasType', $value, null, null);
        return $this;
    }

    public function tree(bool $value = true): static
    {
        $this->condition('tree', $value, null, null);
        return $this;
    }
}
