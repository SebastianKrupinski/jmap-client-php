<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests;

use JmapClient\Requests\Interfaces\RequestPatchInterface;
use stdClass;

class RequestPatch implements RequestPatchInterface
{
    protected object $_parameters;

    public function __construct(&$parameters = null)
    {
        if ($parameters === null) {
            $this->_parameters = new stdClass();
        } else {
            $this->_parameters = & $parameters;
        }
    }

    public function bind(&$anchor): static
    {
        $anchor = $this->_parameters;
        return $this;
    }

    public function path(string $path, mixed $value): static
    {
        $this->_parameters->$path = $value;
        return $this;
    }

    public function parametersRaw(array $value): static
    {
        $this->_parameters = (object) $value;
        return $this;
    }
}