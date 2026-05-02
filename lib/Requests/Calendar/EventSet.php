<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Calendar;

use JmapClient\Requests\Interfaces\RequestPatchInterface;
use JmapClient\Requests\RequestSet;

/**
 * @extends RequestSet<EventParameters>
 */
class EventSet extends RequestSet
{
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
    public function create(string $id, mixed $object = null): EventParameters
    {
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
    public function update(string $id, mixed $object = null): EventParameters
    {
        return parent::update($id, $object);
    }

    /**
     * Patch an event
     *
     * @param string $id Event identifier
     * @param EventParameters|RequestPatchInterface|null $object Optional structured or patch object
     * @return RequestPatchInterface The patch object for method chaining
     */
    public function patch(string $id, mixed $object = null): RequestPatchInterface
    {
        return parent::patch($id, $object);
    }

    /**
     * Delete an event
     *
     * @param string $id Event identifier
     * @return self
     */
    public function delete(string $id): static
    {
        return parent::delete($id);
    }
}
