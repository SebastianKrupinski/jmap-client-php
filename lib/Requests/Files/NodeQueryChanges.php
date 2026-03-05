<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Files;

use JmapClient\Requests\RequestQueryChanges;

class NodeQueryChanges extends RequestQueryChanges
{
    protected string $_space = 'urn:ietf:params:jmap:filenode';
    protected string $_class = 'FileNode';
    protected string $_filterClass = NodeFilter::class;
    protected string $_sortClass = NodeSort::class;

    public function filter(): NodeFilter
    {
        return parent::filter();
    }

    public function sort(): NodeSort
    {
        return parent::sort();
    }
}
