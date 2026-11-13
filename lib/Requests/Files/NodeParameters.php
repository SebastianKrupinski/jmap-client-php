<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Files;

use DateTimeInterface;
use JmapClient\Enums\Files\NodeKind;
use JmapClient\Enums\Files\NodeRole;
use JmapClient\Requests\RequestDate;
use JmapClient\Requests\RequestParameters;

class NodeParameters extends RequestParameters
{
    private const PARAMETER_LIST = [
        '@type',
        'parentId',
        'blobId',
        'name',
        'mediaType',
        'target',
        'role',
        'created',
        'modified',
        'accessed',
        'executable',
        'isSubscribed',
        'shareWith',
        'metadata',
    ];

    public function parametersRaw(array $value): static
    {
        $this->_parameters = (object) array_intersect_key($value, array_flip(self::PARAMETER_LIST));
        return $this;
    }

    public function kind(NodeKind $value): static
    {
        $this->parameter('@type', $value->value);
        return $this;
    }

    public function in(string|null $value): static
    {
        $this->parameter('parentId', $value);
        return $this;
    }

    public function label(string $value): static
    {
        $this->parameter('name', $value);
        return $this;
    }

    public function blob(string|null $value): static
    {
        $this->parameter('blobId', $value);
        return $this;
    }

    public function type(string|null $value): static
    {
        $this->parameter('mediaType', $value);
        return $this;
    }

    public function target(array $value): static
    {
        $this->parameter('target', $value);
        return $this;
    }

    public function role(NodeRole|string|null $value): static
    {
        $this->parameter('role', $value instanceof NodeRole ? $value->value : $value);
        return $this;
    }

    public function created(DateTimeInterface $value): static
    {
        $this->parameter('created', RequestDate::utc($value));
        return $this;
    }

    public function modified(DateTimeInterface $value): static
    {
        $this->parameter('modified', RequestDate::utc($value));
        return $this;
    }

    public function accessed(DateTimeInterface $value): static
    {
        $this->parameter('accessed', RequestDate::utc($value));
        return $this;
    }

    public function executable(bool $value): static
    {
        $this->parameter('executable', $value);
        return $this;
    }

    public function subscribed(bool $value): static
    {
        $this->parameter('isSubscribed', $value);
        return $this;
    }

    public function sharees(string $id, ?NodePermissions $permissions = null): NodePermissions
    {
        if (!isset($this->_parameters->shareWith) || !is_object($this->_parameters->shareWith)) {
            $this->_parameters->shareWith = new \stdClass();
        }
        if ($permissions !== null) {
            $permissions->bind($this->_parameters->shareWith->$id);
        } elseif (!isset($this->_parameters->shareWith->$id)) {
            $this->_parameters->shareWith->$id = new \stdClass();
        }
        return new NodePermissions($this->_parameters->shareWith->$id);
    }
}
