<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Mail;

/**
 * Whether a recipient has displayed a submitted message, from read receipts (RFC 8621 Section 7)
 */
enum MailDisplayStatus: string
{
    case Unknown = 'unknown';
    case Yes = 'yes';
}
