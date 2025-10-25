<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestGet;

class MailThreadGet extends RequestGet {

    protected string $_space = 'urn:ietf:params:jmap:mail';
    protected string $_class = 'Thread';

}
