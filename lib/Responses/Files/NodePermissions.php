<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Files;

use JmapClient\Responses\ResponsePermissions;

class NodePermissions extends ResponsePermissions
{
    public function read(): bool|null
    {
        return $this->parameter('mayRead');
    }

    public function add(): bool|null
    {
        return $this->parameter('mayAddChildren');
    }

    public function rename(): bool|null
    {
        return $this->parameter('mayRename');
    }

    public function delete(): bool|null
    {
        return $this->parameter('mayDelete');
    }

    public function write(): bool|null
    {
        return $this->parameter('mayModifyContent');
    }

    public function share(): bool|null
    {
        return $this->parameter('mayShare');
    }
}
