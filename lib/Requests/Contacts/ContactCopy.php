<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestCopy;

/**
 * ContactCard/copy (RFC 9610)
 *
 * @extends RequestCopy<ContactParameters>
 */
class ContactCopy extends RequestCopy
{
    protected string $_space = 'urn:ietf:params:jmap:contacts';
    protected string $_class = 'ContactCard';
    protected string $_parametersClass = ContactParameters::class;

    public function copy(string $id, string $source, mixed $object = null): ContactParameters
    {
        return parent::copy($id, $source, $object);
    }
}
