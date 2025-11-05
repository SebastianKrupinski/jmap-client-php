<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Calendar;

use DateInterval;
use DateTimeInterface;
use JmapClient\Requests\Request;
use JmapClient\Requests\RequestParameters;

class EventCommonParameters extends RequestParameters
{
    public const DATE_FORMAT_LOCAL = Request::DATE_FORMAT_LOCAL;
    public const DATE_FORMAT_UTC = Request::DATE_FORMAT_UTC;

    /* Metadata Properties */

    public function sequence(int $value): static
    {
        $this->parameter('sequence', $value);
        return $this;
    }

    public function relation(string $value): static
    {
        $this->parameterStructured('relatedTo', $value, true);
        return $this;
    }

    /* Scheduling Properties */

    public function starts(DateTimeInterface $value): static
    {
        $this->parameter('start', $value->format(self::DATE_FORMAT_LOCAL));
        return $this;
    }

    public function duration(DateInterval $value): static
    {
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

    public function timezone(string $value): static
    {
        $this->parameter('timeZone', $value);
        return $this;
    }

    public function timeless(bool $value): static
    {
        $this->parameter('showWithoutTime', $value);
        return $this;
    }

    public function priority(int $value): static
    {
        $this->parameter('priority', $value);
        return $this;
    }

    public function privacy(string $value): static
    {
        $this->parameter('privacy', $value);
        return $this;
    }

    public function availability(string $value): static
    {
        $this->parameter('freeBusyStatus', $value);
        return $this;
    }

    public function replies(array $value): static
    {
        $this->parameter('replyTo', $value);
        return $this;
    }

    public function sender(string $value): static
    {
        $this->parameter('sentBy', $value);
        return $this;
    }

    public function participants(string $id): EventParticipantParameters
    {
        if (!isset($this->_parameters->participants?->$id)) {
            $this->parameterStructured('participants', $id, new \stdClass());
        }
        return new EventParticipantParameters($this->_parameters->participants->$id);
    }

    /* What Properties */

    public function label(string $value): static
    {
        $this->parameter('title', $value);
        return $this;
    }

    public function descriptionContents(string $value): static
    {
        $this->parameter('description', $value);
        return $this;
    }

    public function descriptionType(string $value): static
    {
        $this->parameter('descriptionContentType', $value);
        return $this;
    }

    /* Where Properties */

    public function physicalLocations(string $id): EventPhysicalLocationParameters
    {
        if (!isset($this->_parameters->locations?->$id)) {
            $this->parameterStructured('locations', $id, new \stdClass());
        }
        return new EventPhysicalLocationParameters($this->_parameters->locations->$id);
    }

    public function virtualLocations(string $id): EventVirtualLocationParameters
    {
        if (!isset($this->_parameters->locations?->$id)) {
            $this->parameterStructured('locations', $id, new \stdClass());
        }
        return new EventVirtualLocationParameters($this->_parameters->locations->$id);
    }

    public function links(string ...$value): static
    {
        // @todo Implement
        return $this;
    }

    /* Localization Properties */

    public function locale(string $value): static
    {
        $this->parameter('locale', $value);
        return $this;
    }

    public function localizations(object ...$value): static
    {
        // @todo Implement
        return $this;
    }

    /* Categorization Properties */

    public function color(string $value): static
    {
        $this->parameter('color', $value);
        return $this;
    }

    public function categories(string ...$value): static
    {
        foreach ($value as $entry) {
            $collection[$entry] = true;
        }
        $this->parameter('categories', (object)$collection);
        return $this;
    }

    public function tags(string ...$value): static
    {
        foreach ($value as $entry) {
            $collection[$entry] = true;
        }
        $this->parameter('keywords', (object)$collection);
        return $this;
    }

    /* Notification Properties */

    public function notifications(string $id): EventNotificationParameters
    {
        if (!isset($this->_parameters->alerts?->$id)) {
            $this->parameterStructured('alerts', $id, new \stdClass());
        }
        return new EventNotificationParameters($this->_parameters->alerts->$id);
    }

}
