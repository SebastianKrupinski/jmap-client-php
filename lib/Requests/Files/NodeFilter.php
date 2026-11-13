<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Files;

use DateTimeInterface;
use JmapClient\Requests\RequestDate;
use JmapClient\Requests\RequestFilter;

class NodeFilter extends RequestFilter
{
    // Hierarchy Filters

    public function in(string | null $value): static
    {
        if (is_null($value) || $value === 'root') {
            $this->condition('isTopLevel', true);
        } else {
            $this->condition('parentId', $value);
        }
        return $this;
    }

    public function descendantOf(string $value): static
    {
        $this->condition('ancestorId', $value);
        return $this;
    }

    public function ancestorOf(string $value): static
    {
        $this->condition('descendantId', $value);
        return $this;
    }

    // Chronology Filters

    public function createdBefore(DateTimeInterface $value): static
    {
        $this->condition('createdBefore', RequestDate::utc($value));
        return $this;
    }

    public function createdAfter(DateTimeInterface $value): static
    {
        $this->condition('createdAfter', RequestDate::utc($value));
        return $this;
    }

    public function modifiedBefore(DateTimeInterface $value): static
    {
        $this->condition('modifiedBefore', RequestDate::utc($value));
        return $this;
    }

    public function modifiedAfter(DateTimeInterface $value): static
    {
        $this->condition('modifiedAfter', RequestDate::utc($value));
        return $this;
    }

    public function accessedBefore(DateTimeInterface $value): static
    {
        $this->condition('accessedBefore', RequestDate::utc($value));
        return $this;
    }

    public function accessedAfter(DateTimeInterface $value): static
    {
        $this->condition('accessedAfter', RequestDate::utc($value));
        return $this;
    }

    // Content Filters

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

    // Property Filters

    public function kind(NodeKind $value): static
    {
        $this->condition('nodeType', $value->value);
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

    public function labelIs(string $value): static
    {
        $this->condition('name', $value);
        return $this;
    }

    public function labelMatches(string $value): static
    {
        $this->condition('nameMatch', $value);
        return $this;
    }

    public function formatIs(string $value): static
    {
        $this->condition('mediaType', $value);
        return $this;
    }

    public function formatMatches(string $value): static
    {
        $this->condition('mediaTypeMatch', $value);
        return $this;
    }

    public function hasRole(string $value): static
    {
        $this->condition('role', $value);
        return $this;
    }

    public function hasRoles(bool $value): static
    {
        $this->condition('hasAnyRole', $value);
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
}
