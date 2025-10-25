<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests;

use stdClass;

class RequestParameters
{
    public const DATE_FORMAT_LOCAL = Request::DATE_FORMAT_LOCAL;
    public const DATE_FORMAT_UTC = Request::DATE_FORMAT_UTC;
    
    protected object $_parameters;

    public function __construct(&$parameters = null) {

        if ($parameters === null) {
            $this->_parameters = new stdClass();
        } else {
            $this->_parameters =& $parameters;
        }

    }

    public function bind(&$anchor): self {

        $anchor = $this->_parameters;

        return $this;

    }

    public function parameter(string $name, mixed $value): self {
        
        $this->_parameters->$name = $value;
        
        return $this;

    }

    public function parameterStructured(string $name, string $label, mixed $value): self {
        
        if (!is_object($this->_parameters->$name)) {
            $this->_parameters->$name = new stdClass();
        }
        $this->_parameters->$name->$label = $value;
        
        return $this;

    }

    public function parameterCollection(string $name, mixed $value): self {
        
        if (!is_array($this->_parameters->$name)) {
            $this->_parameters->$name = [];
        }
        $this->_parameters->$name[] = $value;

        return $this;

    }

    public function parametersRaw(array $value): self {

        $this->_parameters = (object) $value;

        return $this;

    }

}
