<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Calendar;

/**
 * Whether an alert trigger or a location relates to the start or the end of an event (RFC 8984)
 */
enum EventRelativeTo: string
{
    case Start = 'start';
    case End = 'end';
}
