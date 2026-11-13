<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Calendar;

/**
 * Recurrence rule frequency (RFC 8984 Section 4.3.3)
 */
enum EventFrequency: string
{
    case Yearly = 'yearly';
    case Monthly = 'monthly';
    case Weekly = 'weekly';
    case Daily = 'daily';
    case Hourly = 'hourly';
    case Minutely = 'minutely';
    case Secondly = 'secondly';
}
