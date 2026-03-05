<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Files;

use JmapClient\Requests\RequestFilter;

class NodeFilter extends RequestFilter
{
    public function isTopLevel(bool $value): static
    {
        $this->condition('isTopLevel', $value);
        return $this;
    }

    public function in(string $value): static
    {
        $this->condition('parentId', $value);
        return $this;
    }

    public function ancestor(string $value): static
    {
        $this->condition('ancestorId', $value);
        return $this;
    }

    public function hasType(bool $value): static
    {
        $this->condition('hasType', $value);
        return $this;
    }

    public function hasRole(string $value): static
    {
        $this->condition('hasRole', $value);
        return $this;
    }

    public function hasAnyRole(bool $value): static
    {
        $this->condition('hasAnyRole', $value);
        return $this;
    }

    public function blob(string $value): static
    {
        $this->condition('blobId', $value);
        return $this;
    }

    public function executable(bool $value): static
    {
        $this->condition('isExecutable', $value);
        return $this;
    }

    public function createdBefore(string $value): static
    {
        $this->condition('createdBefore', $value);
        return $this;
    }

    public function createdAfter(string $value): static
    {
        $this->condition('createdAfter', $value);
        return $this;
    }

    public function modifiedBefore(string $value): static
    {
        $this->condition('modifiedBefore', $value);
        return $this;
    }

    public function modifiedAfter(string $value): static
    {
        $this->condition('modifiedAfter', $value);
        return $this;
    }

    public function accessedBefore(string $value): static
    {
        $this->condition('accessedBefore', $value);
        return $this;
    }

    public function accessedAfter(string $value): static
    {
        $this->condition('accessedAfter', $value);
        return $this;
    }

    public function minSize(int $value): static
    {
        $this->condition('minSize', $value);
        return $this;
    }

    public function maxSize(int $value): static
    {
        $this->condition('maxSize', $value);
        return $this;
    }

    public function label(string $value): static
    {
        $this->condition('name', $value);
        return $this;
    }

    public function labelMatch(string $value): static
    {
        $this->condition('nameMatch', $value);
        return $this;
    }

    public function type(string $value): static
    {
        $this->condition('type', $value);
        return $this;
    }

    public function typeMatch(string $value): static
    {
        $this->condition('typeMatch', $value);
        return $this;
    }

    public function body(string $value): static
    {
        $this->condition('body', $value);
        return $this;
    }

    public function text(string $value): static
    {
        $this->condition('text', $value);
        return $this;
    }
}
