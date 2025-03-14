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

use DateTime;
use DateTimeImmutable;
use JmapClient\Requests\RequestParameters;

class EventRecurrenceRuleParameters extends RequestParameters
{

    public function __construct(&$parameters = null) {

        parent::__construct($parameters);

        $this->parameter('@type', 'RecurrenceRule');

    }

    public function frequency(string $value): self {
        
        $this->parameter('frequency', $value);
        
        return $this;

    }

    public function interval(int $value): self {
        
        $this->parameter('interval', $value);
        
        return $this;

    }

    public function count(int $value): self {
        
        $this->parameter('count', $value);
        
        return $this;

    }

    public function until(DateTime|DateTimeImmutable $value): self {
        
        $this->parameter('until', $value->format(self::DATE_FORMAT_LOCAL));
        
        return $this;

    }

    public function scale(string $value): self {
        
        $this->parameter('rscale', $value);
        
        return $this;

    }

    public function byDayOfWeek(int $id = null): EventRecurrenceDayParameters {
        
        // evaluate if parameter exist and create if needed
        if (!isset($this->_parameters->byDay)) {
            $this->_parameters->byDay = [];
        }
        // return self for function chaining 
        if ($id) {
            if (!isset($this->_parameters->byDay[$id])){
                $this->_parameters->byDay[$id] = new \stdClass();
            }
            return new EventRecurrenceDayParameters($this->_parameters->byDay[$id]);
        } else {
            $this->_parameters->byDay[] = new \stdClass();
            return new EventRecurrenceDayParameters(
                $this->_parameters->byDay[array_key_last($this->_parameters->byDay)]
            );
        }
        
    }

    public function byDayOfMonth(int ...$value): self {
        
        $this->parameter('byMonthDay', $value);
        
        return $this;

    }

    public function byDayOfYear(int ...$value): self {
        
        $this->parameter('byYearDay', $value);
        
        return $this;

    }

    public function byWeekOfYear(int ...$value): self {
        
        $this->parameter('byWeekNo', $value);
        
        return $this;

    }

    public function byMonthOfYear(int ...$value): self {
        
        $this->parameter('byMonth', $value);
        
        return $this;

    }

    public function byHour(int ...$value): self {
        
        $this->parameter('byHour', $value);
        
        return $this;

    }

    public function byMinute(int ...$value): self {
        
        $this->parameter('byMinute', $value);
        
        return $this;

    }

    public function bySecond(int ...$value): self {
        
        $this->parameter('bySecond', $value);
        
        return $this;

    }

    
    public function byPosition(int ...$value): self {
        
        $this->parameter('bySetPosition', $value);
        
        return $this;

    }

}
