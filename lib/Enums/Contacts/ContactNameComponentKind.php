<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Contacts;

/**
 * Name component kinds (RFC 9553 Section 2.2.1), servers may use other values
 */
enum ContactNameComponentKind: string
{
    case Title = 'title';
    case Given = 'given';
    case Given2 = 'given2';
    case Surname = 'surname';
    case Surname2 = 'surname2';
    case Credential = 'credential';
    case Generation = 'generation';
    case Separator = 'separator';
}
