<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Calendar;

/**
 * iTIP scheduling method (RFC 5546)
 */
enum EventMethod: string
{
    case Publish = 'publish';
    case Request = 'request';
    case Reply = 'reply';
    case Add = 'add';
    case Cancel = 'cancel';
    case Refresh = 'refresh';
    case Counter = 'counter';
    case DeclineCounter = 'declinecounter';
}
