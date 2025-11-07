<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests;

use JmapClient\Requests\Interfaces\RequestParametersInterface;
use stdClass;

class RequestParameters implements RequestParametersInterface
{
    public const DATE_FORMAT_LOCAL = Request::DATE_FORMAT_LOCAL;
    public const DATE_FORMAT_UTC = Request::DATE_FORMAT_UTC;

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

    protected function parameter(string $name, mixed $value): static
    {
        $this->_parameters->$name = $value;
        return $this;
    }

    protected function parameterStructured(string $name, string $label, mixed $value): static
    {
        if (!isset($this->_parameters->$name) || !is_object($this->_parameters->$name)) {
            $this->_parameters->$name = new stdClass();
        }
        $this->_parameters->$name->$label = $value;

        return $this;
    }

    protected function parameterCollection(string $name, mixed $value): static
    {
        if (!isset($this->_parameters->$name) || !is_array($this->_parameters->$name)) {
            $this->_parameters->$name = [];
        }
        $this->_parameters->$name[] = $value;

        return $this;
    }

    public function parametersRaw(array $value): static
    {
        $this->_parameters = (object) $value;
        return $this;
    }
}
