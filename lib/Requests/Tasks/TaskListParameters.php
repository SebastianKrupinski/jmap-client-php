<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Tasks;

use JmapClient\Requests\RequestParameters;

class TaskListParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {

        parent::__construct($parameters);

    }

    public function label(string $value): static
    {


        $this->parameter('name', $value);

        return $this;

    }

    public function description(string $value): static
    {


        $this->parameter('description', $value);

        return $this;

    }

    public function color(string $value): static
    {


        $this->parameter('color', $value);

        return $this;

    }

    public function priority(int $value): static
    {


        $this->parameter('sortOrder', $value);

        return $this;

    }

    public function subscribed(bool $value): static
    {


        $this->parameter('isSubscribed', $value);

        return $this;

    }

    public function visible(bool $value): static
    {


        $this->parameter('isVisible', $value);

        return $this;

    }

    public function default(bool $value): static
    {


        $this->parameter('isDefault', $value);

        return $this;

    }

    public function timezone(string $value): static
    {


        $this->parameter('timeZone', $value);

        return $this;

    }

    public function sharees(string ...$value): static
    {


        $this->parameter('shareWith', $value);

        return $this;

    }

}
