<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Contacts;

/**
 * Resource kinds, photo, sound and logo for media, calendar and freeBusy for calendars, contact for links, directory and entry for directories (RFC 9553), servers may use other values
 */
enum ContactResourceKind: string
{
    case Photo = 'photo';
    case Sound = 'sound';
    case Logo = 'logo';
    case Calendar = 'calendar';
    case FreeBusy = 'freeBusy';
    case Contact = 'contact';
    case Directory = 'directory';
    case Entry = 'entry';
}
