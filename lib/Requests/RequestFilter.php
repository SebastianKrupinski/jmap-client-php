<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests;

class RequestFilter
{

    public const DATE_FORMAT_LOCAL = Request::DATE_FORMAT_LOCAL;
    public const DATE_FORMAT_UTC = Request::DATE_FORMAT_UTC;

    protected object $_filter;

    public function __construct(object &$filter) {

        $this->_filter = &$filter;

    }

    public function condition(string $property, mixed $value): self {

        // creates or updates parameter and assigns value
        $this->_filter->$property = $value;
        // return self for function chaining
        return $this;

    }

}
