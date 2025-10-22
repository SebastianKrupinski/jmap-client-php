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
use JmapClient\Requests\Calendar\EventParameters;

class EventSet extends RequestSet {

    protected string $_space = 'urn:ietf:params:jmap:calendars';
    protected string $_class = 'CalendarEvent';
    protected string $_parametersClass = EventParameters::class;

    /**
     * Create an event
     * 
     * @param string $id Event identifier
     * @param EventParameters|null $object Event parameters object
     * 
     * @return EventParameters The event parameters for method chaining
     */
    public function create(string $id, $object = null): EventParameters {
        return parent::create($id, $object);
    }

    /**
     * Update an event
     * 
     * @param string $id Event identifier
     * @param EventParameters|null $object Event parameters object
     * 
     * @return EventParameters The event parameters for method chaining
     */
    public function update(string $id, $object = null): EventParameters {
        return parent::update($id, $object);
    }

    /**
     * Delete an event
     * 
     * @param string $id Event identifier
     * 
     * @return self
     */
    public function delete(string $id): self {
        return parent::delete($id);
    }

}
