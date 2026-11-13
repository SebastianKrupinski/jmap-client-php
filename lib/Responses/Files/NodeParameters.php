<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Files;

use DateTimeImmutable;
use JmapClient\Enums\Files\NodeKind;
use JmapClient\Enums\Files\NodeRole;
use JmapClient\Responses\ResponseDate;
use JmapClient\Responses\ResponseParameters;

class NodeParameters extends ResponseParameters
{
    public function id(): string|null
    {
        return $this->parameter('id');
    }

    public function kind(): NodeKind|null
    {
        $value = $this->parameter('@type');
        return is_string($value) ? NodeKind::tryFrom($value) : null;
    }

    public function in(): string|null
    {
        return $this->parameter('parentId');
    }

    public function label(): string|null
    {
        return $this->parameter('name');
    }

    public function blob(): string|null
    {
        return $this->parameter('blobId');
    }

    public function size(): int|null
    {
        return $this->parameter('size');
    }

    public function type(): string|null
    {
        return $this->parameter('mediaType');
    }

    public function target(): array|null
    {
        return $this->parameter('target');
    }

    public function role(): NodeRole|string|null
    {
        $value = $this->parameter('role');
        return is_string($value) ? (NodeRole::tryFrom($value) ?? $value) : null;
    }

    public function created(): DateTimeImmutable|null
    {
        return ResponseDate::utc($this->parameter('created'));
    }

    public function modified(): DateTimeImmutable|null
    {
        return ResponseDate::utc($this->parameter('modified'));
    }

    public function changed(): DateTimeImmutable|null
    {
        return ResponseDate::utc($this->parameter('changed'));
    }

    public function accessed(): DateTimeImmutable|null
    {
        return ResponseDate::utc($this->parameter('accessed'));
    }

    public function executable(): bool|null
    {
        return $this->parameter('executable');
    }

    public function subscribed(): bool|null
    {
        return $this->parameter('isSubscribed');
    }

    public function rights(): NodePermissions|null
    {
        $rights = $this->parameter('myRights');
        if ($rights === null) {
            return null;
        }
        return new NodePermissions((array) $rights);
    }

    public function sharees(): array|null
    {
        return $this->parameter('shareWith');
    }
}
