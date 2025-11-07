<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Calendar;

use DateTimeInterface;
use JmapClient\Requests\RequestFilter;

class EventFilter extends RequestFilter
{
    public function in(string $value): static
    {
        $this->condition('inCalendar', $value);
        return $this;
    }

    public function uid(string $value): static
    {
        $this->condition('uid', $value);
        return $this;
    }

    public function after(DateTimeInterface $value): static
    {
        $this->condition('after', $value->format(self::DATE_FORMAT_LOCAL));
        return $this;
    }

    public function before(DateTimeInterface $value): static
    {
        $this->condition('before', $value->format(self::DATE_FORMAT_LOCAL));
        return $this;
    }

    public function text(string $value): static
    {
        $this->condition('text', $value);
        return $this;
    }

    public function title(string $value): static
    {
        $this->condition('title', $value);
        return $this;
    }

    public function description(string $value): static
    {
        $this->condition('description', $value);
        return $this;
    }

    public function location(string $value): static
    {
        $this->condition('location', $value);
        return $this;
    }

    public function owner(string $value): static
    {
        $this->condition('owner', $value);
        return $this;
    }

    public function attendee(string $value): static
    {
        $this->condition('attendee', $value);
        return $this;
    }

    public function participation(string $value): static
    {
        $this->condition('participationStatus', $value);
        return $this;
    }
}
