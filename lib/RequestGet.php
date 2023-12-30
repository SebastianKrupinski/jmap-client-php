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
namespace JmapClient;

class RequestGet extends Request
{

    public function __construct(string $class, string $account, string $identifier = '') {

        parent::__construct($class, 'get', $account, $identifier);
        
    }

    public function appendId(string $id): RequestGet {

        // creates or updates parameter and assigns new value
        $this->_request[1]['ids'][] = $id;
        // return self for function chaining 
        return $this;
    }

    public function removeId(string $id): RequestGet {

        if (isset($this->_request[1]['ids']) && ($key = array_search($id, $this->_request[1]['ids'])) !== false) {
            // remove id 
            unset($this->_request[1]['ids'][$key]);
            // remove id collection if empty
            if (count($this->_request[1]['ids']) === 0) {
                unset($this->_request[1]['ids']);
            }
        }
        // return self for function chaining
        return $this;
    }

    public function appendProperty(string $id): RequestGet {

        // creates or updates parameter and assigns new value
        $this->_request[1]['properties'][] = $id;
        // return self for function chaining 
        return $this;
    }

    public function removeProperty(string $id): RequestGet {

        if (isset($this->_request[1]['properties']) && ($key = array_search($id, $this->_request[1]['properties'])) !== false) {
            // remove id 
            unset($this->_request[1]['properties'][$key]);
            // remove id collection if empty
            if (count($this->_request[1]['properties']) === 0) {
                unset($this->_request[1]['properties']);
            }
        }
        // return self for function chaining
        return $this;
    }

}
