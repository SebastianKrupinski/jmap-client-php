<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Calendar;

/**
 * Day of the week in a recurrence rule (RFC 8984 Section 4.3.3)
 */
enum EventWeekday: string
{
    case Monday = 'mo';
    case Tuesday = 'tu';
    case Wednesday = 'we';
    case Thursday = 'th';
    case Friday = 'fr';
    case Saturday = 'sa';
    case Sunday = 'su';
}
