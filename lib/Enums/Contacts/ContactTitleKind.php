<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Contacts;

/**
 * Title kinds (RFC 9553 Section 2.2.5), servers may use other values
 */
enum ContactTitleKind: string
{
    case Title = 'title';
    case Role = 'role';
}
