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

        // creates or updates parameter and assigns value
        $this->condition('inCalendar', $value);
        // return self for function chaining
        return $this;

    }

    public function uid(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('uid', $value);
        // return self for function chaining
        return $this;

    }
    
    public function after(DateTimeInterface $value): self {

        // creates or updates parameter and assigns value
        $this->condition('after', $value->format(self::DATE_FORMAT_LOCAL));
        // return self for function chaining
        return $this;

    }

    public function before(DateTimeInterface $value): self {

        // creates or updates parameter and assigns value
        $this->condition('before', $value->format(self::DATE_FORMAT_LOCAL));
        // return self for function chaining
        return $this;

    }

    public function text(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('text', $value);
        // return self for function chaining
        return $this;

    }

    public function title(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('title', $value);
        // return self for function chaining
        return $this;

    }

    public function description(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('description', $value);
        // return self for function chaining
        return $this;

    }

    public function location(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('location', $value);
        // return self for function chaining
        return $this;

    }

    public function owner(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('owner', $value);
        // return self for function chaining
        return $this;

    }

    public function attendee(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('attendee', $value);
        // return self for function chaining
        return $this;

    }

    public function participation(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('participationStatus', $value);
        // return self for function chaining
        return $this;

    }

}
