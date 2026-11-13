<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Calendar;

/**
 * Participant roles (RFC 8984 Section 4.4.6), servers may use other values
 */
enum EventParticipantRole: string
{
    case Owner = 'owner';
    case Attendee = 'attendee';
    case Optional = 'optional';
    case Informational = 'informational';
    case Chair = 'chair';
    case Contact = 'contact';
}
