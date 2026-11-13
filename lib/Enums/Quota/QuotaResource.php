<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Quota;

/**
 * What a quota counts (RFC 9425 Section 4.1)
 */
enum QuotaResource: string
{
    /** Number of objects */
    case Count = 'count';
    /** Size in octets */
    case Octets = 'octets';
}
