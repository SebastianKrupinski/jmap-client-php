<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Mail;

/**
 * EmailSubmission undoStatus values (RFC 8621 Section 7)
 */
enum MailSubmissionUndoStatus: string
{
    /** The message may still be canceled */
    case Pending = 'pending';
    /** The message has been relayed and can no longer be canceled */
    case Final = 'final';
    /** The submission was canceled and the message will not be delivered */
    case Canceled = 'canceled';
}
