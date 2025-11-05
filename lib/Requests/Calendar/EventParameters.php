<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Calendar;

use DateTimeInterface;

/**
 * EventParameters class for building JMAP Calendar Event objects
 *
 * @package JmapClient\Requests\Calendar
 */
class EventParameters extends EventCommonParameters
{
    /**
     * Constructor
     *
     * @param object|null $parameters Reference to a parameters object to bind to
     */
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'Event');
    }

    /**
     * Set the type of the calendar object
     *
     * @param string $value The type value (e.g., 'Event')
     * @return static Returns the current instance for method chaining
     */
    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    /* Metadata Properties */

    /**
     * Set the calendar ID(s) that the event belongs to
     *
     * @param string $value The calendar ID
     * @return self Returns the current instance for method chaining
     */
    public function in(string $value): static
    {
        $this->parameterStructured('calendarIds', $value, true);
        return $this;
    }

    /**
     * Set the unique identifier (UID) for the event
     *
     * @param string $value The UID value
     * @return self Returns the current instance for method chaining
     */
    public function uid(string $value): static
    {
        $this->parameter('uid', $value);
        return $this;
    }

    /**
     * Set the method property for the event (e.g., REQUEST, PUBLISH, CANCEL)
     *
     * @param string $value The method value
     * @return self Returns the current instance for method chaining
     */
    public function method(string $value): static
    {
        $this->parameter('method', $value);
        return $this;
    }

    /**
     * Set the creation date and time for the event
     *
     * @param DateTimeInterface $value The creation date/time
     * @return self Returns the current instance for method chaining
     */
    public function created(DateTimeInterface $value): static
    {
        $this->parameter('created', $value->format(self::DATE_FORMAT_UTC));
        return $this;
    }

    /**
     * Set the last updated date and time for the event
     *
     * @param DateTimeInterface $value The update date/time
     * @return self Returns the current instance for method chaining
     */
    public function updated(DateTimeInterface $value): static
    {
        $this->parameter('updated', $value->format(self::DATE_FORMAT_UTC));
        return $this;
    }

    /* Scheduling Properties */

    /**
     * Get or create a recurrence rule parameters object for the event
     *
     * This method returns an EventRecurrenceRuleParameters object that can be used
     * to define recurring patterns for the event.
     *
     * @return EventRecurrenceRuleParameters The recurrence rule parameters object
     */
    public function recurrenceRule(): EventRecurrenceRuleParameters
    {
        if (!isset($this->_parameters->recurrenceRule)) {
            $this->_parameters->recurrenceRule = new \stdClass();
        }
        return new EventRecurrenceRuleParameters($this->_parameters->recurrenceRule);
    }

    /**
     * Set exceptions/exclusions to the recurrence rule
     *
     * @param object ...$value One or more exclusion objects
     * @return self Returns the current instance for method chaining
     * @todo Implement this method
     */
    public function recurrenceExclusions(object ...$value): static
    {
        // @todo Implement this method
        return $this;
    }

    /**
     * Get or create a recurrence mutation (override) for a specific date
     *
     * This method allows you to define exceptions or modifications to specific
     * occurrences of a recurring event.
     *
     * @param DateTimeInterface $date The date/time of the occurrence to override
     * @param EventMutationParameters|null $object Optional mutation parameters to bind
     * @return EventMutationParameters The mutation parameters object for the specified date
     */
    public function recurrenceMutations(DateTimeInterface $date, ?EventMutationParameters $object = null): EventMutationParameters
    {
        $id = $date->format(self::DATE_FORMAT_LOCAL);
        if (!isset($this->_parameters->recurrenceOverrides->$id)) {
            $this->parameterStructured('recurrenceOverrides', $id, new \stdClass());
        }
        if ($object !== null) {
            $object->bind($this->_parameters->recurrenceOverrides->$id);
        }
        return new EventMutationParameters($this->_parameters->recurrenceOverrides->$id);
    }

}
