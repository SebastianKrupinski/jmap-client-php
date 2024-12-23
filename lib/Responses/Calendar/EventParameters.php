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
use DateTimeImmutable;
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

    public function created(): DateTimeImmutable|null {
        
        $value = $this->parameter('created');
        return ($value) ? new DateTimeImmutable($value) : null;

    }

    public function updated(): DateTimeImmutable|null {
        
        $value = $this->parameter('updated');
        return ($value) ? new DateTimeImmutable($value) : null;

    }

    public function sequence(): int {
        
        return (int)$this->parameter('sequence');

    }

    public function relation(): string|null {
        
        return $this->parameter('relatedTo');

    }

    /* Scheduling Properties */

    public function starts(): DateTimeImmutable|null {
        
        $value = $this->parameter('start');
        return ($value) ? new DateTimeImmutable($value) : null;

    }

    public function ends(): DateTimeImmutable|null {
        
        $starts = $this->starts();
        $duration = $this->duration();

        if ($starts && $duration) {
            return $starts->add($duration);
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

    
    public function recurrenceInstanceId(): string|null {
        
        return $this->parameter('recurrenceId');

    }

    public function recurrenceInstanceTimeZone(): string|null {
        
        return $this->parameter('recurrenceIdTimeZone');

    }

    public function recurrenceRules(): array {
        
        $collection = $this->parameter('recurrenceRules') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new EventRecurrenceRuleParameters($data);
        }

        return $collection;

    }

    public function recurrenceExclusions(): array {
        
        return $this->parameter('excludedRecurrenceRules') ?? [];

    }

    public function recurrenceOverrides(): array {
        
        return $this->parameter('recurrenceOverrides') ?? [];

    }

    public function priority(): int {
        
        return (int)$this->parameter('priority') ?? 0;

    }

    public function privacy(): string {
        
        return $this->parameter('privacy') ?? 'public';

    }

    public function availability(): string {
        
        return $this->parameter('freeBusyStatus') ?? 'busy';

    }
    
    public function replies(): array {
        
        return $this->parameter('replyTo') ?? [];

    }

    public function sender(): string|null {
        
        return $this->parameter('sentBy');

    }

    public function participants(): array {
        
        $collection = $this->parameter('participants') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new EventParticipantParameters($data);
        }

        return $collection;

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

    public function physicalLocations(): array {

        $collection = $this->parameter('locations') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new EventPhysicalLocationParameters($data);
        }

        return $collection;

    }

    public function virtualLocations(): array {
        
        $collection = $this->parameter('virtualLocations') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new EventVirtualLocationParameters($data);
        }

        return $collection;

    }

    /* Localization Properties */
    
    public function locale(): string|null {
        
        return $this->parameter('locale');

    }

    public function localizations(): array {
        
        return $this->parameter('localizations');

    }

    /* Categorization Properties */

    public function color(): string|null {
        
        return $this->parameter('color');

    }

    public function categories(): array {
        
        return $this->parameter('categories') ?? [];

    }

    public function tags(): array {
        
        return $this->parameter('keywords') ?? [];

    }

    /* Notification Properties */

    public function notifications(): array {
        
        $collection = $this->parameter('alerts') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new EventNotificationParameters($data);
        }

        return $collection;

    }

}
