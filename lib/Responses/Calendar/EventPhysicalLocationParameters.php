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
namespace JmapClient\Responses\Calendar;

use JmapClient\Responses\ResponseParameters;

class EventPhysicalLocationParameters extends ResponseParameters {

    public function label(): string|null {
        return $this->parameter('name');
    }

    public function description(): string|null {
        return $this->parameter('description');
    }

    public function timezone(): string|null {
        return $this->parameter('timeZone');
    }

    public function coordinates(): string|null {
        return $this->parameter('coordinates');
    }
    
    public function attributes(): array {
        return $this->parameter('locationTypes');
    }

    public function links(): array {
        return $this->parameter('links');
    }

    public function relative(): string|null {
        return $this->parameter('relativeTo');
    }

}
