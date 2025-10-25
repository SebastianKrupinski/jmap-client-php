<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
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

        
        $this->_command['onDestroyRemoveEvents'] = $value;

        return $this;
        
    }

}
