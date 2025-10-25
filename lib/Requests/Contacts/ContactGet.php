<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestGet;

class ContactGet extends RequestGet {

    protected string $_space = 'urn:ietf:params:jmap:contacts';
    protected string $_class = 'ContactCard';
    
}
