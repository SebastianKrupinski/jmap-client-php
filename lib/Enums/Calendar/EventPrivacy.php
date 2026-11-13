<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Calendar;

/**
 * Event privacy (RFC 8984 Section 4.4.3), servers may use other values
 */
enum EventPrivacy: string
{
    case Public = 'public';
    case Private = 'private';
    case Secret = 'secret';
}
