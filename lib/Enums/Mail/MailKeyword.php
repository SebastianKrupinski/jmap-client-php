<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Mail;

/**
 * Common email keywords (RFC 8621 and the IANA IMAP and JMAP Keywords registry), custom keywords are also allowed
 */
enum MailKeyword: string
{
    case Seen = '$seen';
    case Flagged = '$flagged';
    case Draft = '$draft';
    case Answered = '$answered';
    case Forwarded = '$forwarded';
    case Junk = '$junk';
    case NotJunk = '$notjunk';
    case Phishing = '$phishing';
    case Important = '$important';
    case MdnSent = '$mdnsent';
}
