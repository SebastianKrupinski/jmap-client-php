<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Calendar;

use JmapClient\Requests\RequestParameters;

class EventParticipantIdentityParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
    }

    /**
     * Set the display name of the participant
     *
     * @param string $value The display name (e.g. "Jane Doe")
     *
     * @return static
     */
    public function name(string $value): static
    {
        return $this->parameter('name', $value);
    }

    /**
     * Set the calendar address URI for this participant
     *
     * @param string $value The calendar address (e.g. "mailto:jane@example.com")
     *
     * @return static
     */
    public function calendarAddress(string $value): static
    {
        return $this->parameter('calendarAddress', $value);
    }
}
