<?php
declare(strict_types=1);

/**
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
*
* @author Sebastian Krupinski <krupinski01@gmail.com>
*
* @license AGPL-3.0-or-later
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
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
        
        // creates or updates parameter and assigns value
        $this->_parameters->$name = $value;
        // return self for function chaining
        return $this;

    }

    public function parameterStructured(string $name, string $label, mixed $value): self {
        
        // evaluate if parameter is an object
        if (!is_object($this->_parameters->$name)) {
            $this->_parameters->$name = new stdClass();
        }
        // creates or updates parameter and assigns value
        $this->_parameters->$name->$label = $value;
        // return self for function chaining
        return $this;

    }

    public function parameterCollection(string $name, mixed $value): self {
        
        // evaluate if parameter is an object
        if (!is_array($this->_parameters->$name)) {
            $this->_parameters->$name = [];
        }
        // creates or updates parameter and assigns value
        $this->_parameters->$name[] = $value;
        // return self for function chaining
        return $this;

    }

    public function parametersRaw(array $value): self {

        // creates or updates parameter and assigns value
        $this->_parameters = (object) $value;
        // return self for function chaining
        return $this;

    }

}
