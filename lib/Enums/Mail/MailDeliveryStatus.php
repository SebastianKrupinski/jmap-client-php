<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Mail;

/**
 * Whether a submitted message was delivered to a recipient (RFC 8621 Section 7)
 */
enum MailDeliveryStatus: string
{
    case Queued = 'queued';
    case Yes = 'yes';
    case No = 'no';
    case Unknown = 'unknown';
}
