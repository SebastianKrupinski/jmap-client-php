<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Files;

use JmapClient\Requests\RequestSet;

/**
 * @extends RequestSet<NodeParameters>
 */
class NodeSet extends RequestSet
{
    protected string $_space = 'urn:ietf:params:jmap:filenode';
    protected string $_class = 'FileNode';
    protected string $_parametersClass = NodeParameters::class;

    public function create(string $id, mixed $object = null): NodeParameters
    {
        return parent::create($id, $object);
    }

    public function update(string $id, mixed $object = null): NodeParameters
    {
        return parent::update($id, $object);
    }


    public function delete(string $id): static
    {
        return parent::delete($id);
    }

    public function destroyChildren(bool $value): static
    {
        $this->_command['onDestroyRemoveChildren'] = $value;
        return $this;
    }

    public function onExists(string|null $value): static
    {
        $this->_command['onExists'] = $value;
        return $this;
    }
}
