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

use DateTimeInterface;
use JmapClient\Requests\RequestParameters;

class EventParameters extends RequestParameters
{
    public function __construct(&$request, $action, $id) {

        parent::__construct($request, $action, $id);

    }

    public function type(): string {

        return 'application/jscalendar+json;type=event';

    }

    public function in(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('calendarIds', $value, true);
        // return self for function chaining
        return $this;

    }

    public function uid(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('uid', $value);
        // return self for function chaining
        return $this;

    }

    public function sequence(int $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('sequence', $value);
        // return self for function chaining
        return $this;

    }

    public function related(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('relatedTo', $value, true);
        // return self for function chaining
        return $this;

    }

    public function label(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('title', $value);
        // return self for function chaining
        return $this;

    }

    public function description(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('description', $value);
        // return self for function chaining
        return $this;

    }

    public function timeless(bool $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('showWithoutTime', $value);
        // return self for function chaining
        return $this;

    }

    public function physicalLocations(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('location', $value);
        // return self for function chaining
        return $this;

    }

    public function virtualLocations(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('virtualLocations', $value);
        // return self for function chaining
        return $this;

    }

    public function links(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('links', $value);
        // return self for function chaining
        return $this;

    }

    public function locale(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('locale', $value);
        // return self for function chaining
        return $this;

    }

    public function keywords(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('keywords', $value);
        // return self for function chaining
        return $this;

    }

    public function categories(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('categories', $value);
        // return self for function chaining
        return $this;

    }

    public function color(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('color', $value);
        // return self for function chaining
        return $this;

    }


}
