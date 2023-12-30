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

class SortCondition implements \JsonSerializable
{
    protected object $_condition;

    public function __construct () {
        $this->_condition = new stdClass();
    }

    public function jsonSerialize() {
        return $this->_condition;
    }

    public function property(string $value): SortCondition {

        // create property and assign value
        $this->_condition->property = $value;
        // return self for function chaining
        return $this;

    }

    public function direction(bool $value): SortCondition {

        // create property and assign value
        $this->_condition->isAscending = $value;
        // return self for function chaining
        return $this;

    }

    public function keyword(string $value): SortCondition {

        // create property and assign value
        $this->_condition->keyword = $value;
        // return self for function chaining
        return $this;

    }

    public function collation(string $value): SortCondition {

        // create property and assign value
        $this->_condition->collation = $value;
        // return self for function chaining
        return $this;

    }

}
