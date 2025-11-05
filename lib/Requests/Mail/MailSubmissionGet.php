<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestGet;

class MailSubmissionGet extends RequestGet
{
    protected string $_space = 'urn:ietf:params:jmap:submission';
    protected string $_class = 'EmailSubmission';

}
