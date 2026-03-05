<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Files;

use JmapClient\Responses\ResponsePermissions;

class NodePermissions extends ResponsePermissions
{
    public function read(): bool|null
    {
        return $this->parameter('mayRead');
    }

    public function write(): bool|null
    {
        return $this->parameter('mayWrite');
    }

    public function share(): bool|null
    {
        return $this->parameter('mayShare');
    }
}
