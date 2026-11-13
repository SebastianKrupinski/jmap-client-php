<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Contacts;

/**
 * Contexts a contact property applies in (RFC 9553 Section 1.5.1), billing and delivery apply to addresses only, servers may use other values
 */
enum ContactContext: string
{
    case Work = 'work';
    case Private = 'private';
    case Billing = 'billing';
    case Delivery = 'delivery';
}
