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

class RequestQuery extends Request
{

    public function __construct(string $class, string $account, string $identifier = '') {

        parent::__construct($class, 'query', $account, $identifier);
        
    }

    public function filter(FilterCondition $condition) {

        // creates id collection if missing and appends new id
        $this->_request[1]['filter'] = $condition;
        // return self for function chaining 
        return $this;

    }

    public function sort(SortCondition $condition) {

        // creates id collection if missing and appends new id
        $this->_request[1]['sort'][] = $condition;
        // return self for function chaining 
        return $this;

    }

    public function limit(int $value) {

        // creates id collection if missing and appends new id
        $this->_request[1]['limit'] = $value;
        // return self for function chaining 
        return $this;

    }

    public function position(int $value) {

        // creates id collection if missing and appends new id
        $this->_request[1]['position'] = $value;
        // return self for function chaining 
        return $this;

    }

    public function anchor(string $value) {

        // creates id collection if missing and appends new id
        $this->_request[1]['anchor'] = $value;
        // return self for function chaining 
        return $this;

    }

    public function anchorOffset(int $value) {

        // creates id collection if missing and appends new id
        $this->_request[1]['anchorOffset'] = $value;
        // return self for function chaining 
        return $this;

    }

}
