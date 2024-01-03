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

use JmapClient\Requests\RequestFilter;

class EventFilter extends RequestFilter
{

    public function __construct(&$request) {

        parent::__construct($request);
        
    }

    public function in(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('inCalendars', $value);
        // return self for function chaining
        return $this;

    }

    public function uid(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('uid', $value);
        // return self for function chaining
        return $this;

    }
    
    public function after(\DateTime $value): self {

        // creates or updates parameter and assigns value
        $this->condition('after', $value->format('Y-m-d\\TH:i:s'));
        // return self for function chaining
        return $this;

    }

    public function before(\DateTime $value): self {

        // creates or updates parameter and assigns value
        $this->condition('before', $value->format('Y-m-d\\TH:i:s'));
        // return self for function chaining
        return $this;

    }

    public function text(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('text', $value);
        // return self for function chaining
        return $this;

    }

    public function title(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('title', $value);
        // return self for function chaining
        return $this;

    }

    public function description(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('description', $value);
        // return self for function chaining
        return $this;

    }

    public function location(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('location', $value);
        // return self for function chaining
        return $this;

    }

    public function owner(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('owner', $value);
        // return self for function chaining
        return $this;

    }

    public function attendee(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('attendee', $value);
        // return self for function chaining
        return $this;

    }

    public function participation(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('participationStatus', $value);
        // return self for function chaining
        return $this;

    }

}
