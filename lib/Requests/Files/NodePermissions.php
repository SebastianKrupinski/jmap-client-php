<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
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
        $this->parameter('mayModifyContent', $value);
        return $this;
    }

    public function add(bool $value): static
    {
        $this->parameter('mayAddChildren', $value);
        return $this;
    }

    public function rename(bool $value): static
    {
        $this->parameter('mayRename', $value);
        return $this;
    }

    public function delete(bool $value): static
    {
        $this->parameter('mayDelete', $value);
        return $this;
    }

    public function share(bool $value): static
    {
        $this->parameter('mayShare', $value);
        return $this;
    }
}
