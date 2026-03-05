<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Files;

use JmapClient\Requests\RequestPermissions;

class NodePermissions extends RequestPermissions
{
    
    public function read(bool $value): static
    {
        $this->parameter('mayRead', $value);
        return $this;
    }

    public function write(bool $value): static
    {
        $this->parameter('mayWrite', $value);
        return $this;
    }

    public function share(bool $value): static
    {
        $this->parameter('mayShare', $value);
        return $this;
    }
}
