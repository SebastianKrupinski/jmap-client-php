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

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use JmapClient\Requests\Request;
use JmapClient\Requests\RequestParameters;

class EventParameters extends RequestParameters {

    public const DATE_FORMAT_LOCAL = Request::DATE_FORMAT_LOCAL;
    public const DATE_FORMAT_UTC = Request::DATE_FORMAT_UTC;

    public function type(): string {
        return 'application/jscalendar+json;type=event';
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

    public function created(DateTime|DateTimeImmutable $value): self {
        $this->parameter('created', $value->format(self::DATE_FORMAT_UTC));
        return $this;
    }

    public function updated(DateTime|DateTimeImmutable $value): self {
        $this->parameter('updated', $value->format(self::DATE_FORMAT_UTC));
        return $this;
    }

    public function sequence(int $value): self {
        $this->parameter('sequence', $value);
        return $this;
    }

    public function relation(string $value): self {
        $this->parameterStructured('relatedTo', $value, true);
        return $this;
    }

    /* Scheduling Properties */

    public function timezone(string $value): self {
        $this->parameter('timeZone', $value);
        return $this;
    }

    public function starts(DateTime|DateTimeImmutable $value): self {
        $this->parameter('start', $value->format(self::DATE_FORMAT_LOCAL));
        return $this;
    }

    public function duration(DateInterval $value): self {
        $format = '%rP';
        if ($value->y > 0) {
            $format .= '%yY';
        }
        if ($value->m > 0) {
            $format .= '%mM';
        }
        if ($value->d > 0) {
            $format .= '%dD';
        }
        if ($value->h > 0 || $value->i > 0) {
            $format .= 'T';
            if ($value->h > 0) {
                $format .= '%hH';
            }
            if ($value->i > 0) {
                $format .= '%iM';
            }
        }
        $this->parameter('duration', $value->format($format));
        return $this;
    }

    public function timeless(bool $value): self {
        $this->parameter('showWithoutTime', $value);
        return $this;
    }

    public function recurrenceInstanceId(string $value): self {
        $this->parameter('recurrenceId', $value);
        return $this;
    }

    public function recurrenceInstanceTimeZone(string $value): self {
        $this->parameter('recurrenceIdTimeZone', $value);
        return $this;
    }

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

    public function recurrenceOverrides(DateTimeInterface $date): EventParameters {
        $id = $date->format(self::DATE_FORMAT_LOCAL);
        if (!isset($this->_parameters->recurrenceOverrides?->$id)) {
            $this->parameterStructured('recurrenceOverrides', $id, new \stdClass());
        }
        return new EventParameters($this->_parameters->recurrenceOverrides->$id);
    }

    public function priority(int $value): self {
        $this->parameter('priority', $value);
        return $this;
    }
    
    public function privacy(string $value): self {
        $this->parameter('privacy', $value);
        return $this;
    }

    public function availability(string $value): self {
        $this->parameter('freeBusyStatus', $value);
        return $this;
    }

    public function replies(array $value): self {
        $this->parameter('replyTo', $value);
        return $this;
    }

    public function sender(string $value): self {
        $this->parameter('sentBy', $value);
        return $this;
    }

    public function participants(string $id): EventParticipantParameters {
        if (!isset($this->_parameters->participants?->$id)) {
            $this->parameterStructured('participants', $id, new \stdClass());
        }
        return new EventParticipantParameters($this->_parameters->participants->$id);
    }

    /* What Properties */

    public function label(string $value): self {
        $this->parameter('title', $value);
        return $this;
    }

    public function descriptionContents(string $value): self {
        $this->parameter('description', $value);
        return $this;
    }

    public function descriptionType(string $value): self {
        $this->parameter('descriptionContentType', $value);
        return $this;
    }

    /* Where Properties */

    public function physicalLocations(string $id): EventPhysicalLocationParameters {
        if (!isset($this->_parameters->locations?->$id)) {
            $this->parameterStructured('locations', $id, new \stdClass());
        }
        return new EventPhysicalLocationParameters($this->_parameters->locations->$id);
    }

    public function virtualLocations(string $id): EventVirtualLocationParameters {
        if (!isset($this->_parameters->locations?->$id)) {
            $this->parameterStructured('locations', $id, new \stdClass());
        }
        return new EventVirtualLocationParameters($this->_parameters->locations->$id);
    }

    public function links(string $value): self {
        $this->parameter('links', $value);
        return $this;
    }

    /* Localization Properties */

    public function locale(string $value): self {
        $this->parameter('locale', $value);
        return $this;
    }

    public function localizations(object ...$value): self {
        // @todo Implement
        return $this;
    }

    /* Categorization Properties */

    public function color(string $value): self {
        $this->parameter('color', $value);
        return $this;
    }

    public function categories(string ...$value): self {
        foreach ($value as $entry) {
            $collection[$entry] = true;
        }
        $this->parameter('categories', (object)$collection);
        return $this;
    }

    public function tags(string ...$value): self {
        foreach ($value as $entry) {
            $collection[$entry] = true;
        }
        $this->parameter('keywords', (object)$collection);
        return $this;
    }

    /* Notification Properties */

    public function notifications(string $id): EventNotificationParameters {
        if (!isset($this->_parameters->alerts?->$id)) {
            $this->parameterStructured('alerts', $id, new \stdClass());
        }
        return new EventNotificationParameters($this->_parameters->alerts->$id);
    }

}
