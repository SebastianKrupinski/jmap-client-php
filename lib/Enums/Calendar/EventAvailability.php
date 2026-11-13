<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Calendar;

/**
 * Whether an event blocks time, the freeBusyStatus property (RFC 8984 Section 4.4.2), servers may use other values
 */
enum EventAvailability: string
{
    case Free = 'free';
    case Busy = 'busy';
}
