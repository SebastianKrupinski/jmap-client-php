<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Files;

use JmapClient\Requests\RequestGet;

class NodeGet extends RequestGet
{
    protected string $_space = 'urn:ietf:params:jmap:filenode';
    protected string $_class = 'FileNode';

    /**
     * Also return all ancestors of the requested nodes
     */
    public function withAncestors(bool $value): static
    {
        $this->_command['fetchParents'] = $value;
        return $this;
    }
}
