<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Files;

use JmapClient\Enums\Files\NodeOnExists;
use JmapClient\Requests\RequestCopy;

/**
 * FileNode/copy, copying a Directory copies its whole subtree
 *
 * @extends RequestCopy<NodeParameters>
 */
class NodeCopy extends RequestCopy
{
    protected string $_space = 'urn:ietf:params:jmap:filenode';
    protected string $_class = 'FileNode';
    protected string $_parametersClass = NodeParameters::class;

    public function copy(string $id, string $source, mixed $object = null): NodeParameters
    {
        return parent::copy($id, $source, $object);
    }

    public function destroyRecursive(bool $value): static
    {
        $this->_command['onDestroyRemoveChildren'] = $value;
        return $this;
    }

    public function onExists(NodeOnExists|null $value): static
    {
        $this->_command['onExists'] = $value?->value;
        return $this;
    }

    public function compareCaseInsensitively(bool $value): static
    {
        $this->_command['compareCaseInsensitively'] = $value;
        return $this;
    }
}
