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
namespace JmapClient\Responses\Calendar;

use JmapClient\Responses\ResponseParameters;

class EventRecurrenceRuleParameters extends ResponseParameters {
    
    public function frequency(): string|null {
        return $this->parameter('frequency');
    }

    public function interval(): int {
        return $this->parameter('interval') ?? 1;
    }

    public function count(): int|null {
        return $this->parameter('count');
    }

    public function until(): string|null {
        return $this->parameter('until');
    }

    public function scale(): string {
        return $this->parameter('rscale') ?? 'gregorian';
    }

    public function byDayOfWeek(): array {
        return $this->parameter('byDay') ?? [];
    }

    public function byDayOfMonth(): array {
        return $this->parameter('byMonthDay') ?? [];
    }

    public function byDayOfYear(): array {
        return $this->parameter('byYearDay') ?? [];
    }

    public function byWeekOfYear(): array {
        return $this->parameter('byWeekNo') ?? [];
    }

    public function byMonthOfYear(): array {
        return $this->parameter('byMonth') ?? [];
    }

    public function byHour(): array {
        return $this->parameter('byHour') ?? [];
    }
    
    public function byMinute(): array {
        return $this->parameter('byMinute') ?? [];
    }

    public function bySecond(): array {
        return $this->parameter('bySecond') ?? [];
    }

    public function byPosition(): array {
        return $this->parameter('bySetPosition') ?? [];
    }

}
