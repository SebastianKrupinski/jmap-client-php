<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestQueryChanges;

class MailboxQueryChanges extends RequestQueryChanges
{
    protected string $_space = 'urn:ietf:params:jmap:mail';
    protected string $_class = 'Mailbox';
    protected string $_filterClass = MailboxFilter::class;
    protected string $_sortClass = MailboxSort::class;

    public function filter(): MailboxFilter
    {
        return parent::filter();
    }

    public function sort(): MailboxSort
    {
        return parent::sort();
    }
}
