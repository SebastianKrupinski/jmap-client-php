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
use JmapClient\Requests\Request;
use JmapClient\Requests\RequestParameters;

class EventParameters extends RequestParameters
{
    public const DATE_FORMAT_LOCAL = Request::DATE_FORMAT_LOCAL;
    public const DATE_FORMAT_UTC = Request::DATE_FORMAT_UTC;

    public function __construct(&$parameters = null) {

        parent::__construct($parameters);

    }

    public function type(): string {

        return 'application/jscalendar+json;type=event';

    }

    /* Metadata Properties */

    public function in(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('calendarIds', $value, true);
        // return self for function chaining
        return $this;

    }

    public function uid(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('uid', $value);
        // return self for function chaining
        return $this;

    }

    public function method(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('method', $value);
        // return self for function chaining
        return $this;

    }

    public function created(DateTime|DateTimeImmutable $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('created', $value->format(self::DATE_FORMAT_UTC));
        // return self for function chaining
        return $this;

    }

    public function updated(DateTime|DateTimeImmutable $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('updated', $value->format(self::DATE_FORMAT_UTC));
        // return self for function chaining
        return $this;

    }

    public function sequence(int $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('sequence', $value);
        // return self for function chaining
        return $this;

    }

    public function relation(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('relatedTo', $value, true);
        // return self for function chaining
        return $this;

    }

    /* Scheduling Properties */

    public function timezone(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('timeZone', $value);
        // return self for function chaining
        return $this;

    }

    public function starts(DateTime|DateTimeImmutable $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('start', $value->format(self::DATE_FORMAT_LOCAL));
        // return self for function chaining
        return $this;

    }

    public function duration(DateInterval $value): self {
        
        // creates or updates parameter and assigns value
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
        // return self for function chaining
        return $this;

    }

    public function timeless(bool $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('showWithoutTime', $value);
        // return self for function chaining
        return $this;

    }

    public function recurrenceInstanceId(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('recurrenceId', $value);
        // return self for function chaining
        return $this;

    }

    public function recurrenceInstanceTimeZone(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('recurrenceIdTimeZone', $value);
        // return self for function chaining
        return $this;

    }

    public function recurrenceRules(int $id = null): EventRecurrenceRuleParameters {
        
        // evaluate if parameter exist and create if needed
        if (!isset($this->_parameters->recurrenceRules)) {
            $this->_parameters->recurrenceRules = [];
        }
        // return self for function chaining 
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
        
        // return self for function chaining
        return $this;

    }

    public function recurrenceOverrides(object ...$value): self {
        
        // return self for function chaining
        return $this;

    }

    public function priority(int $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('priority', $value);
        // return self for function chaining
        return $this;

    }
    
    public function privacy(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('privacy', $value);
        // return self for function chaining
        return $this;

    }

    public function availability(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('freeBusyStatus', $value);
        // return self for function chaining
        return $this;

    }

    public function replies(array $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('replyTo', $value);
        // return self for function chaining
        return $this;

    }

    public function sender(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('sentBy', $value);
        // return self for function chaining
        return $this;

    }

    public function participants(string $id): EventParticipantParameters {
        
        // evaluate if parameter exist and create if needed
        if (!isset($this->_parameters->participants?->$id)) {
            $this->parameterStructured('participants', $id, new \stdClass());
        }
        // return self for function chaining 
        return new EventParticipantParameters($this->_parameters->participants->$id);

    }

    /* What Properties */

    public function label(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('title', $value);
        // return self for function chaining
        return $this;

    }

    public function descriptionContents(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('description', $value);
        // return self for function chaining
        return $this;

    }

    public function descriptionType(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('descriptionContentType', $value);
        // return self for function chaining
        return $this;

    }

    /* Where Properties */

    public function physicalLocations(string $id): EventPhysicalLocationParameters {

        // evaluate if parameter exist and create if needed
        if (!isset($this->_parameters->locations?->$id)) {
            $this->parameterStructured('locations', $id, new \stdClass());
        }
        // return self for function chaining 
        return new EventPhysicalLocationParameters($this->_parameters->locations->$id);

    }

    public function virtualLocations(string $id): EventVirtualLocationParameters {

        // evaluate if parameter exist and create if needed
        if (!isset($this->_parameters->locations?->$id)) {
            $this->parameterStructured('locations', $id, new \stdClass());
        }
        // return self for function chaining 
        return new EventVirtualLocationParameters($this->_parameters->locations->$id);

    }

    public function links(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('links', $value);
        // return self for function chaining
        return $this;

    }

    /* Localization Properties */

    public function locale(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('locale', $value);
        // return self for function chaining
        return $this;

    }

    public function localizations(object ...$value): self {
        
        // return self for function chaining
        return $this;

    }

    /* Categorization Properties */

    public function color(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('color', $value);
        // return self for function chaining
        return $this;

    }

    public function categories(string ...$value): self {
        
        foreach ($value as $entry) {
            $collection[$entry] = true;
        }
        // creates or updates parameter and assigns value
        $this->parameter('categories', (object)$collection);
        // return self for function chaining
        return $this;

    }

    public function tags(string ...$value): self {
        
        foreach ($value as $entry) {
            $collection[$entry] = true;
        }
        // creates or updates parameter and assigns value
        $this->parameter('keywords', (object)$collection);
        // return self for function chaining
        return $this;

    }

    /* Notification Properties */

    public function notifications(string $id): EventNotificationParameters {
        
        // evaluate if parameter exist and create if needed
        if (!isset($this->_parameters->alerts?->$id)) {
            $this->parameterStructured('alerts', $id, new \stdClass());
        }
        // return self for function chaining 
        return new EventNotificationParameters($this->_parameters->alerts->$id);

    }

}
