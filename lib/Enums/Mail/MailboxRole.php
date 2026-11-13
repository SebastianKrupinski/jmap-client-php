<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Mail;

/**
 * Mailbox roles from the IANA IMAP Mailbox Name Attributes registry, servers may use other roles
 */
enum MailboxRole: string
{
    case Inbox = 'inbox';
    case Archive = 'archive';
    case Drafts = 'drafts';
    case Sent = 'sent';
    case Trash = 'trash';
    case Junk = 'junk';
    case Important = 'important';
    case All = 'all';
    case Flagged = 'flagged';
    case Memos = 'memos';
    case Scheduled = 'scheduled';
    case Snoozed = 'snoozed';
}
