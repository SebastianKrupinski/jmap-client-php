<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Files;

use JmapClient\Responses\ResponseParameters;

class NodeParameters extends ResponseParameters
{
    public function id(): string|null
    {
        return $this->parameter('id');
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
        return $this->parameter('type');
    }

    public function role(): string|null
    {
        return $this->parameter('role');
    }

    public function created(): string|null
    {
        return $this->parameter('created');
    }

    public function modified(): string|null
    {
        return $this->parameter('modified');
    }

    public function accessed(): string|null
    {
        return $this->parameter('accessed');
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
