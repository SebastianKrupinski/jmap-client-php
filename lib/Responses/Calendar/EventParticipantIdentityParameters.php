<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Calendar;

use JmapClient\Responses\ResponseParameters;

class EventParticipantIdentityParameters extends ResponseParameters
{
    /**
     * Get the participant identity ID
     *
     * @return string|null
     */
    public function id(): ?string
    {
        return $this->parameter('id');
    }

    /**
     * Get the display name of the participant
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->parameter('name');
    }

    /**
     * Get the calendar address URI for this participant
     *
     * @return string|null
     */
    public function calendarAddress(): ?string
    {
        return $this->parameter('calendarAddress');
    }

    /**
     * Check if this is the default participant identity
     *
     * @return bool|null
     */
    public function isDefault(): ?bool
    {
        return $this->parameter('isDefault');
    }
}
