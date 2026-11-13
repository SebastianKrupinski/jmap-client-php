<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Mail;

/**
 * Forms an email header can be parsed into (RFC 8621 Section 4.1.2)
 */
enum MailHeaderForm: string
{
    case Raw = 'asRaw';
    case Text = 'asText';
    case Addresses = 'asAddresses';
    case GroupedAddresses = 'asGroupedAddresses';
    case MessageIds = 'asMessageIds';
    case Date = 'asDate';
    case URLs = 'asURLs';
}
