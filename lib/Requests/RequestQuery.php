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

class RequestQuery extends Request {

    protected string $_method = 'query';
    
    public function filter(): RequestFilter {
        if (!isset($this->_command['filter'])) {
            $this->_command['filter'] = new \stdClass();
        }
        return new RequestFilter($this->_command['filter']);
    }

    public function sort(): RequestSort {
        if (!isset($this->_command['sort'])) {
            $this->_command['sort'] = [];
        }
        return new RequestSort($this->_command['sort']);
    }

    public function limitAbsolute(?int $position = null, ?int $count = null): self {
        if ($position !== null) {
            $this->_command['position'] = $position;
        }
        if ($count !== null) {
            $this->_command['limit'] = $count;
        }
        return $this;
    }

    public function limitRelative(?int $anchor = null, ?int $count = null, ?int $offset = null): self {
        if ($anchor !== null) {
            $this->_command['anchor'] = $anchor;
        }
        if ($count !== null) {
            $this->_command['limit'] = $count;
        }
        if ($offset !== null) {
            $this->_command['anchorOffset'] = $offset;
        }
        return $this;
    }
    
    public function tally(bool $value): self {
        $this->_command['calculateTotal'] = $value;
        return $this;
    }

}
