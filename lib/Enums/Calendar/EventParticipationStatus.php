<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Calendar;

/**
 * Participant reply status (RFC 8984 Section 4.4.6), servers may use other values
 */
enum EventParticipationStatus: string
{
    case NeedsAction = 'needs-action';
    case Accepted = 'accepted';
    case Declined = 'declined';
    case Tentative = 'tentative';
    case Delegated = 'delegated';
}
