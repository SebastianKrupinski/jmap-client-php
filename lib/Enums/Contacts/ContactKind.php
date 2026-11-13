<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Contacts;

/**
 * What a contact card describes (RFC 9553 Section 2.1.4), servers may use other values
 */
enum ContactKind: string
{
    case Individual = 'individual';
    case Group = 'group';
    case Org = 'org';
    case Location = 'location';
    case Device = 'device';
    case Application = 'application';
}
