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

use JmapClient\Requests\Request;

class RequestQuery extends Request
{

    public function __construct(string $space, string $class, string $account, string $identifier = '') {

        parent::__construct($space, $class, 'query', $account, $identifier);
        
    }

    public function filter(): RequestFilter {
        
        // return self for function chaining 
        return new RequestFilter($this->_request);

    }

    public function sort(): RequestSort {
        
        // return self for function chaining 
        return new RequestSort($this->_request);

    }

    public function startAbsolute(int $value): self {

        // creates or updates parameter and assigns new value
        $this->_request[1]['position'] = $value;
        // return self for function chaining 
        return $this;

    }
    
    public function limitAbsolute(int $value): self {

        // creates or updates parameter and assigns new value
        $this->_request[1]['limit'] = $value;
        // return self for function chaining 
        return $this;

    }

    public function startRelative(string $value): self {

        // creates or updates parameter and assigns new value
        $this->_request[1]['anchor'] = $value;
        // return self for function chaining 
        return $this;

    }

    public function startRelativeOffset(int $value): self {

        // creates or updates parameter and assigns new value
        $this->_request[1]['anchorOffset'] = $value;
        // return self for function chaining 
        return $this;

    }

    public function limitRelative(int $value): self {

        // creates or updates parameter and assigns new value
        $this->_request[1]['limit'] = $value;
        // return self for function chaining 
        return $this;

    }
    

    public function tally(bool $value): self {

        // creates or updates parameter and assigns new value
        $this->_request[1]['calculateTotal'] = $value;
        // return self for function chaining 
        return $this;

    }

}
