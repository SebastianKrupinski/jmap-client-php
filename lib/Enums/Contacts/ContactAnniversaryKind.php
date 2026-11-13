<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Contacts;

/**
 * Anniversary kinds (RFC 9553 Section 2.8.1), servers may use other values
 */
enum ContactAnniversaryKind: string
{
    case Birth = 'birth';
    case Death = 'death';
    case Wedding = 'wedding';
}
