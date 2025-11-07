<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Tasks;

use JmapClient\Requests\RequestSort;

class TaskSort extends RequestSort
{
    public function created(bool $value = false): static
    {
        $this->condition('created', $value);

        return $this;
    }

    public function updated(bool $value = false): static
    {
        $this->condition('updated', $value);

        return $this;
    }

    public function start(bool $value = false): static
    {
        $this->condition('start', $value);

        return $this;
    }

    public function uid(bool $value = false): static
    {
        $this->condition('uid', $value);

        return $this;
    }
}
