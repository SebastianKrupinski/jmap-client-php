<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Calendar;

use JmapClient\Requests\RequestPermissions;

class CalendarPermissions extends RequestPermissions
{
    public function availability(bool $value): static
    {
        $this->parameter('mayReadFreeBusy', $value);
        return $this;
    }

    public function read(bool $value): static
    {
        $this->parameter('mayReadItems', $value);
        return $this;
    }

    public function write(bool $value): static
    {
        $this->parameter('mayWriteAll', $value);
        return $this;
    }

    public function writeOwn(bool $value): static
    {
        $this->parameter('mayWriteOwn', $value);
        return $this;
    }

    public function writePrivate(bool $value): static
    {
        $this->parameter('mayWritePrivate', $value);
        return $this;
    }

    public function rsvp(bool $value): static
    {
        $this->parameter('mayRSVP', $value);
        return $this;
    }

    public function manage(bool $value): static
    {
        $this->parameter('mayAdmin', $value);
        return $this;
    }

    public function delete(bool $value): static
    {
        $this->parameter('mayDelete', $value);
        return $this;
    }

}
