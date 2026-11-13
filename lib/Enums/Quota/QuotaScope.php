<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Quota;

/**
 * What a quota applies to (RFC 9425 Section 4.1)
 */
enum QuotaScope: string
{
    case Account = 'account';
    case Domain = 'domain';
    case Global = 'global';
}
