<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests;

class RequestFilter
{
    protected object $_filter;

    public function __construct(object &$filter)
    {
        $this->_filter = &$filter;
    }

    public function condition(string $property, mixed $value): static
    {
        $this->_filter->$property = $value;

        return $this;
    }
}
