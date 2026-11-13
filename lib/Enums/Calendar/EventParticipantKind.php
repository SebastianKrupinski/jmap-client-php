<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Calendar;

/**
 * What a participant is (RFC 8984 Section 4.4.6), servers may use other values
 */
enum EventParticipantKind: string
{
    case Individual = 'individual';
    case Group = 'group';
    case Location = 'location';
    case Resource = 'resource';
}
