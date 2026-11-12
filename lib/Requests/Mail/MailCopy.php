<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestCopy;

/**
 * Email/copy (RFC 8621 Section 4.7), mailboxIds, keywords and receivedAt may be overridden on the copy
 *
 * @extends RequestCopy<MailParameters>
 */
class MailCopy extends RequestCopy
{
    protected string $_space = 'urn:ietf:params:jmap:mail';
    protected string $_class = 'Email';
    protected string $_parametersClass = MailParameters::class;

    public function copy(string $id, string $source, mixed $object = null): MailParameters
    {
        return parent::copy($id, $source, $object);
    }
}
