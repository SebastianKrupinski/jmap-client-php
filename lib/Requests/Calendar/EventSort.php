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
namespace JmapClient\Requests\Calendar;

use JmapClient\Requests\RequestSort;

class EventSort extends RequestSort
{
    
    public function __construct(&$request) {

        parent::__construct($request);
        
    }
    
    public function created(bool $value = false): self {

        // creates or updates parameter and assigns value
        $this->condition('created', $value);
        // return self for function chaining
        return $this;

    }

    public function updated(bool $value = false): self {

        // creates or updates parameter and assigns value
        $this->condition('updated', $value);
        // return self for function chaining
        return $this;

    }

    public function start(bool $value = false): self {

        // creates or updates parameter and assigns value
        $this->condition('start', $value);
        // return self for function chaining
        return $this;

    }

    public function uid(bool $value = false): self {

        // creates or updates parameter and assigns value
        $this->condition('uid', $value);
        // return self for function chaining
        return $this;

    }

    public function recurrence(bool $value = false): self {

        // creates or updates parameter and assigns value
        $this->condition('recurrenceId', $value);
        // return self for function chaining
        return $this;

    }

}
