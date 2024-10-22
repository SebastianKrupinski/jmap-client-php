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

class RequestQueryChanges extends Request
{

    public function __construct(string $space, string $class, string $account, string $identifier = '') {

        parent::__construct($space, $class, 'queryChanges', $account, $identifier);
        
    }

    public function filter(): RequestFilter {
        
        // evaluate if filter parameter exist and create if needed
        if (!isset($this->_requestCommand['filter'])) {
            $this->_requestCommand['filter'] = new \stdClass();
        }
        // return self for function chaining 
        return new RequestFilter($this->_requestCommand['filter']);

    }

    public function sort(): RequestSort {
        
        // evaluate if filter parameter exist and create if needed
        if (!isset($this->_requestCommand['sort'])) {
            $this->_requestCommand['sort'] = [];
        }
        // return self for function chaining 
        return new RequestSort($this->_requestCommand['sort']);

    }

    public function state(string $value): self {

        // creates or updates parameter and assigns new value
        $this->_requestCommand['sinceQueryState'] = $value;
        // return self for function chaining 
        return $this;

    }

    public function limitRelative(int $value): self {

        // creates or updates parameter and assigns new value
        $this->_requestCommand['maxChanges'] = $value;
        // return self for function chaining 
        return $this;

    }

    public function limitAbsolute(string $value): self {

        // creates or updates parameter and assigns new value
        $this->_requestCommand['upToId'] = $value;
        // return self for function chaining 
        return $this;

    }

    public function tally(bool $value): self {

        // creates or updates parameter and assigns new value
        $this->_requestCommand['calculateTotal'] = $value;
        // return self for function chaining 
        return $this;

    }

}
