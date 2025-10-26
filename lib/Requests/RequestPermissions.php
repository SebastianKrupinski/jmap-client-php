<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests;

use stdClass;

class RequestPermissions
{
    
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

    protected function parameter(string $name, mixed $value): self {
        
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

}
