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

use DateInterval;
use DateTime;
use DateTimeInterface;
use JmapClient\Responses\ResponseParameters;

class EventParameters extends ResponseParameters
{
    
    public function __construct(array $response = []) {

        parent::__construct($response);

    }

    /* Metadata Properties */

    public function in(): array|null {
        
        // return value of parameter
        return array_keys($this->parameter('calendarIds'));

    }
    
    public function id(): string|null {

        return $this->parameter('id');

    }

    public function uid(): string|null {
        
        return $this->parameter('uid');

    }

    public function method(): string|null {
        
        return $this->parameter('method');

    }

    public function created(): DateTimeInterface|null {
        
        $value = $this->parameter('created');
        return ($value) ? new DateTime($value) : null;

    }

    public function updated(): DateTimeInterface|null {
        
        $value = $this->parameter('updated');
        return ($value) ? new DateTime($value) : null;

    }

    public function sequence(): int {
        
        return (int)$this->parameter('sequence');

    }

    public function relation(): string|null {
        
        return $this->parameter('relatedTo');

    }

    /* Scheduling Properties */

    public function starts(): DateTimeInterface|null {
        
        $value = $this->parameter('start');
        return ($value) ? new DateTime($value) : null;

    }

    public function ends(): DateTimeInterface|null {
        
        $starts = $this->starts();
        $duration = $this->duration();

        if ($starts && $duration) {
            return date_add($starts, $duration);
        }

        return null;

    }

    public function duration(): DateInterval|null {
        
        $value = $this->parameter('duration');
        return ($value) ? new DateInterval($value) : null;

    }

    public function timezone(): string|null {
        
        return $this->parameter('timeZone');

    }

    public function timeless(): bool {
        
        return $this->parameter('showWithoutTime');

    }

    public function recurrence(): mixed {
        
        return $this->parameter('recurrenceRules');

    }

    public function recurrenceExclusions(): mixed {
        
        return $this->parameter('excludedRecurrenceRules');

    }

    public function recurrenceOverrides(): mixed {
        
        return $this->parameter('recurrenceOverrides');

    }

    public function priority(): int {
        
        return $this->parameter('priority') ?? 0;

    }

    public function privacy(): string {
        
        return $this->parameter('privacy') ?? 'public';

    }

    public function availability(): string {
        
        return $this->parameter('freeBusyStatus') ?? 'busy';

    }
    
    public function replies(): mixed {
        
        return $this->parameter('replyTo');

    }

    public function sender(): string|null {
        
        return $this->parameter('sentBy');

    }

    public function participants(): mixed {
        
        return $this->parameter('participants');

    }

    /* What Properties */

    public function label(): string {
        
        return (string)$this->parameter('title');

    }

    public function descriptionContents(): string {
        
        return (string)$this->parameter('description');

    }

    public function descriptionType(): string {
        
        return $this->parameter('descriptionContentType') ?? 'text/plain';

    }

    /* Where Properties */

    public function physicalLocation(): mixed {
        
        return $this->parameter('locations');

    }

    public function virtualLocation(): mixed {
        
        return $this->parameter('virtualLocations');

    }

    /* Localization Properties */
    
    public function locale(): string|null {
        
        return $this->parameter('locale');

    }

    public function localizations(): mixed {
        
        return $this->parameter('localizations');

    }

    /* Categorization Properties */

    public function color(): string|null {
        
        return $this->parameter('color');

    }

    public function categories(): mixed {
        
        return $this->parameter('categories');

    }

    public function keywords(): mixed {
        
        return $this->parameter('keywords');

    }

}
