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

class RequestSort
{
    protected array $_sort;

    public function __construct(array &$sort) {

        $this->_sort = &$sort;

    }

    public function condition(string $property, bool|null $direction = null, string|null $keyword = null, string|null $collation = null): self {

        // construct condition
        $condition = new \stdClass();
        $condition->property = $property;
        if ($direction !== null) {
            $condition->isAscending = $direction;
        }
        if ($keyword !== null) {
            $condition->keyword = $keyword;
        }
        if ($collation !== null) {
            $condition->collation = $collation;
        }
        
        // creates or updates parameter and assigns value
        $this->_sort[] = $condition;
        // return self for function chaining
        return $this;

    }

}
