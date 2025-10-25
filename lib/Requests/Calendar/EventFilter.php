<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Calendar;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use JmapClient\Requests\RequestFilter;

class EventFilter extends RequestFilter {

    public function in(string $value): self {

        
        $this->condition('inCalendar', $value);
        
        return $this;

    }

    public function uid(string $value): self {

        
        $this->condition('uid', $value);
        
        return $this;

    }
    
    public function after(DateTimeInterface $value): self {

        
        $this->condition('after', $value->format(self::DATE_FORMAT_LOCAL));
        
        return $this;

    }

    public function before(DateTimeInterface $value): self {

        
        $this->condition('before', $value->format(self::DATE_FORMAT_LOCAL));
        
        return $this;

    }

    public function text(string $value): self {

        
        $this->condition('text', $value);
        
        return $this;

    }

    public function title(string $value): self {

        
        $this->condition('title', $value);
        
        return $this;

    }

    public function description(string $value): self {

        
        $this->condition('description', $value);
        
        return $this;

    }

    public function location(string $value): self {

        
        $this->condition('location', $value);
        
        return $this;

    }

    public function owner(string $value): self {

        
        $this->condition('owner', $value);
        
        return $this;

    }

    public function attendee(string $value): self {

        
        $this->condition('attendee', $value);
        
        return $this;

    }

    public function participation(string $value): self {

        
        $this->condition('participationStatus', $value);
        
        return $this;

    }

}
