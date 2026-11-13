<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Tasks;

use DateTimeInterface;
use JmapClient\Requests\RequestDate;
use JmapClient\Requests\RequestParameters;

class TaskParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
    }

    public function type(): string
    {
        return 'application/jstask+json;type=task';
    }

    /* Metadata Properties */

    public function in(string $value): static
    {
        $this->parameterStructured('taskListId', $value, true);

        return $this;
    }

    public function uid(string $value): static
    {
        $this->parameter('uid', $value);

        return $this;
    }

    public function created(DateTimeInterface $value): static
    {
        $this->parameter('created', RequestDate::utc($value));

        return $this;
    }

    public function updated(DateTimeInterface $value): static
    {
        $this->parameter('updated', RequestDate::utc($value));

        return $this;
    }
}
