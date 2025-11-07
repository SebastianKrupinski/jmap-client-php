<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Calendar;

use JmapClient\Requests\RequestSet;

/**
 * @extends RequestSet<CalendarParameters>
 */
class CalendarSet extends RequestSet
{
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
    public function create(string $id, mixed $object = null): CalendarParameters
    {
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
    public function update(string $id, mixed $object = null): CalendarParameters
    {
        return parent::update($id, $object);
    }

    /**
     * Delete a calendar
     *
     * @param string $id Calendar identifier
     *
     * @return self
     */
    public function delete(string $id): static
    {
        return parent::delete($id);
    }

    /**
     * Flag to delete all events within the calendar when deleting the calendar
     *
     * @param bool $value true yes, false no
     *
     * @return self
     */
    public function deleteContents(bool $value): static
    {
        $this->_command['onDestroyRemoveEvents'] = $value;
        return $this;
    }
}
