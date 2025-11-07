<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestParameters;

class ContactDatePartialParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'PartialDate');
    }

    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    public function year(int $value): static
    {
        $this->parameter('year', $value);
        return $this;
    }

    public function month(int $value): static
    {
        $this->parameter('month', $value);
        return $this;
    }

    public function day(int $value): static
    {
        $this->parameter('day', $value);
        return $this;
    }

    public function scale(string $value): static
    {
        $this->parameter('calendarScale', $value);
        return $this;
    }
}
