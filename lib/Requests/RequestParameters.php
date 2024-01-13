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

class RequestParameters
{
    protected array $_request;
    protected string $_action;
    protected string $_id;

    public function __construct(&$request, $action, $id) {

        $this->_request = &$request;
        $this->_action = $action;
        $this->_id = $id;

        // evaluate if parameter exists
        if (!$this->_request[1][$action] instanceof \stdClass) {
            $this->_request[1][$action] = new \stdClass();
        }
        // evaluate if object exists
        if (!$this->_request[1][$action]->$id instanceof \stdClass) {
            $this->_request[1][$action]->$id = new \stdClass();
        }
        
    }

    public function parameter(string $name, mixed $value): self {
        
        $action = $this->_action;
        $id = $this->_id;
        // creates or updates parameter and assigns value
        $this->_request[1][$action]->$id->$name = $value;
        // return self for function chaining
        return $this;

    }

    public function parameterStructured(string $name, string $label, mixed $value): self {
        
        $action = $this->_action;
        $id = $this->_id;
        // evaluate if parameter is an object
        if (!is_object($this->_request[1][$action]->$id->$name)) {
            $this->_request[1][$action]->$id->$name = new \stdClass();
        }
        // creates or updates parameter and assigns value
        $this->_request[1][$action]->$id->$name->$label = $value;
        // return self for function chaining
        return $this;

    }

    public function parameterCollection(string $name, mixed $value): self {
        
        $action = $this->_action;
        $id = $this->_id;
        // evaluate if parameter is an object
        if (!is_array($this->_request[1][$action]->$id->$name)) {
            $this->_request[1][$action]->$id->$name = [];
        }
        // creates or updates parameter and assigns value
        $this->_request[1][$action]->$id->$name[] = $value;
        // return self for function chaining
        return $this;

    }

}
