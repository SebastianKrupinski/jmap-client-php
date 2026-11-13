<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Calendar;

/**
 * How to send scheduling messages to a participant, the sendTo keys (RFC 8984 Section 4.4.6), servers may use other values
 */
enum EventSendProtocol: string
{
    case Imip = 'imip';
    case Other = 'other';
}
