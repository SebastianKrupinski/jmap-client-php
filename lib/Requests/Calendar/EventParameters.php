<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Calendar;

use DateTimeInterface;

class EventParameters extends EventCommonParameters {

    public function __construct(&$parameters = null) {
        parent::__construct($parameters);
        $this->parameter('@type', 'Event');
    }
    
    public function type(string $value): static {
        $this->parameter('@type', $value);
        return $this;
    }

    /* Metadata Properties */

    public function in(string $value): self {
        $this->parameterStructured('calendarIds', $value, true);
        return $this;
    }

    public function uid(string $value): self {
        $this->parameter('uid', $value);
        return $this;
    }

    public function method(string $value): self {
        $this->parameter('method', $value);
        return $this;
    }

    public function created(DateTimeInterface $value): self {
        $this->parameter('created', $value->format(self::DATE_FORMAT_UTC));
        return $this;
    }

    public function updated(DateTimeInterface $value): self {
        $this->parameter('updated', $value->format(self::DATE_FORMAT_UTC));
        return $this;
    }

    /* Scheduling Properties */

    public function recurrenceRules(?int $id = null): EventRecurrenceRuleParameters {
        if (!isset($this->_parameters->recurrenceRules)) {
            $this->_parameters->recurrenceRules = [];
        }
        if ($id) {
            if (!isset($this->_parameters->recurrenceRules[$id])){
                $this->_parameters->recurrenceRules[$id] = new \stdClass();
            }
            return new EventRecurrenceRuleParameters($this->_parameters->recurrenceRules[$id]);
        } else {
            $this->_parameters->recurrenceRules[] = new \stdClass();
            return new EventRecurrenceRuleParameters(
                $this->_parameters->recurrenceRules[array_key_last($this->_parameters->recurrenceRules)]
            );
        }
    }

    public function recurrenceExclusions(object ...$value): self {
        // @todo Implement this method
        return $this;
    }

    public function recurrenceMutations(DateTimeInterface $date, ?EventMutationParameters $object = null): EventMutationParameters {
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
