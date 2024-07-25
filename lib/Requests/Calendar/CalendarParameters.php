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

use JmapClient\Requests\RequestParameters;

class CalendarParameters extends RequestParameters
{
    public function __construct(&$request, $action, $id) {

        parent::__construct($request, $action, $id);

    }

    public function name(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('name', $value);
        // return self for function chaining
        return $this;

    }

    public function description(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('description', $value);
        // return self for function chaining
        return $this;

    }

    public function color(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('color', $value);
        // return self for function chaining
        return $this;

    }

    public function priority(int $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('sortOrder', $value);
        // return self for function chaining
        return $this;

    }

    public function subscribed(bool $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('isSubscribed', $value);
        // return self for function chaining
        return $this;

    }

    public function visible(bool $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('isVisible', $value);
        // return self for function chaining
        return $this;

    }

    public function default(bool $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('isDefault', $value);
        // return self for function chaining
        return $this;

    }

    public function timezone(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('timeZone', $value);
        // return self for function chaining
        return $this;

    }

    public function sharees(string ...$value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('shareWith', $value);
        // return self for function chaining
        return $this;

    }

}
