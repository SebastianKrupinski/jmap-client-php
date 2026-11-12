<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Calendar;

use JmapClient\Requests\RequestCopy;

/**
 * CalendarEvent/copy
 *
 * @extends RequestCopy<EventParameters>
 */
class EventCopy extends RequestCopy
{
    protected string $_space = 'urn:ietf:params:jmap:calendars';
    protected string $_class = 'CalendarEvent';
    protected string $_parametersClass = EventParameters::class;

    public function copy(string $id, string $source, mixed $object = null): EventParameters
    {
        return parent::copy($id, $source, $object);
    }
}
