<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestChanges;

class MailIdentityChanges extends RequestChanges {

    protected string $_space = 'urn:ietf:params:jmap:submission';
    protected string $_class = 'Identity';

}
