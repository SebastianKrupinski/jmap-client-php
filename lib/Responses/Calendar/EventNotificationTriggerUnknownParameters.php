<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Calendar;

use JmapClient\Responses\ResponseParameters;

class EventNotificationTriggerUnknownParameters extends ResponseParameters
{
    public function type(): string
    {
        return 'unknown';
    }

}
