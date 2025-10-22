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
use DateTimeImmutable;
use DateTimeInterface;
use JmapClient\Responses\ResponseParameters;

class EventCommonParameters extends ResponseParameters {
    
    /* Metadata Properties */

    public function sequence(): int {
        return (int)$this->parameter('sequence');
    }

    public function relation(): string|null {
        return $this->parameter('relatedTo');
    }

    /* Scheduling Properties */

    public function starts(): DateTimeInterface|null {
        $value = $this->parameter('start');
        return ($value) ? new DateTimeImmutable($value) : null;
    }

    public function ends(): DateTimeInterface|null {
        $starts = $this->starts();
        $duration = $this->duration();

        if ($starts && $duration) {
            return $starts->add($duration);
        }

        return null;
    }

    public function duration(): DateInterval|null {
        $value = $this->parameter('duration');

        // if the duration is negative handle properly
        if ($value && $value[0] === '-') {
            $value = substr($value, 1);
            $duration = new DateInterval($value);
            $duration->invert = 1;
            return $duration;
        }

        return ($value) ? new DateInterval($value) : null;
    }

    public function timezone(): string|null {
        return $this->parameter('timeZone');
    }

    public function timeless(): bool {
        return (bool)$this->parameter('showWithoutTime');
    }

    public function priority(): int|null {
        return (int)$this->parameter('priority') ?? null;
    }

    public function privacy(): string|null {
        return $this->parameter('privacy') ?? null;
    }

    public function availability(): string|null {
        return $this->parameter('freeBusyStatus') ?? null;
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

    public function links(): array {
        $collection = $this->parameter('links') ?? [];
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
