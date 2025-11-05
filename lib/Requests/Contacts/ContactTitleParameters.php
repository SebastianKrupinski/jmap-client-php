<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestParameters;

class ContactTitleParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'Title');
    }

    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    public function name(string $value): static
    {
        $this->parameter('name', $value);
        return $this;
    }

    public function kind(string $value): static
    {
        $this->parameter('kind', $value);
        return $this;
    }

    public function relation(string $value): static
    {
        $this->parameter('organizationId', $value);
        return $this;
    }

}
