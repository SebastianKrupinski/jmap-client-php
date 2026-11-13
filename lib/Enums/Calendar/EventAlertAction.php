<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Calendar;

/**
 * How an alert is presented (RFC 8984 Section 4.5.2), servers may use other values
 */
enum EventAlertAction: string
{
    case Display = 'display';
    case Email = 'email';
}
