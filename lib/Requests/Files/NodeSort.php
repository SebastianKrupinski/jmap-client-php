<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Files;

use JmapClient\Requests\RequestSort;

class NodeSort extends RequestSort
{
    public function type(bool $value = true): static
    {
        $this->condition('mediaType', $value, null, null);
        return $this;
    }

    public function kind(bool $value = true): static
    {
        $this->condition('nodeType', $value, null, null);
        return $this;
    }

    public function label(bool $value = true): static
    {
        $this->condition('name', $value, null, null);
        return $this;
    }

    public function created(bool $value = true): static
    {
        $this->condition('created', $value, null, null);
        return $this;
    }

    public function modified(bool $value = true): static
    {
        $this->condition('modified', $value, null, null);
        return $this;
    }

    public function size(bool $value = true): static
    {
        $this->condition('size', $value, null, null);
        return $this;
    }

    public function tree(bool $value = true): static
    {
        $this->condition('tree', $value, null, null);
        return $this;
    }
}
