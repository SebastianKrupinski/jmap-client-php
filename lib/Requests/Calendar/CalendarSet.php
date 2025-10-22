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

use JmapClient\Requests\RequestSet;
use JmapClient\Requests\Calendar\CalendarParameters;

class CalendarSet extends RequestSet {

    protected string $_space = 'urn:ietf:params:jmap:calendars';
    protected string $_class = 'Calendar';
    protected string $_parametersClass = CalendarParameters::class;

    /**
     * Create a calendar
     * 
     * @param string $id Calendar identifier
     * @param CalendarParameters|null $object Calendar parameters object
     * 
     * @return CalendarParameters The calendar parameters for method chaining
     */
    public function create(string $id, $object = null): CalendarParameters {
        return parent::create($id, $object);
    }

    /**
     * Update a calendar
     * 
     * @param string $id Calendar identifier
     * @param CalendarParameters|null $object Calendar parameters object
     * 
     * @return CalendarParameters The calendar parameters for method chaining
     */
    public function update(string $id, $object = null): CalendarParameters {
        return parent::update($id, $object);
    }

    /**
     * Delete a calendar
     * 
     * @param string $id Calendar identifier
     * 
     * @return self
     */
    public function delete(string $id): self {
        return parent::delete($id);
    }

    /**
     * Set whether to remove events when deleting the calendar
     * 
     * @param bool $value Whether to remove events on destruction
     * 
     * @return self
     */
    public function deleteContents(bool $value): self {

        // creates or updates parameter and assigns value
        $this->_command['onDestroyRemoveEvents'] = $value;
        // return self for function chaining 
        return $this;
        
    }

}
