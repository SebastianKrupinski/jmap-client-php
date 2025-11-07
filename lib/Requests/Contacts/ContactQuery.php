<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Contacts;

use DateTimeZone;
use JmapClient\Requests\RequestQuery;

class ContactQuery extends RequestQuery
{
    protected string $_space = 'urn:ietf:params:jmap:contacts';
    protected string $_class = 'ContactCard';
    protected string $_filterClass = ContactFilter::class;
    protected string $_sortClass = ContactSort::class;

    public function filter(): ContactFilter
    {
        return parent::filter();
    }

    public function sort(): ContactSort
    {
        return parent::sort();
    }

    public function timezone(DateTimeZone $value): static
    {
        $this->_command['timeZone'] = $value->getName();

        return $this;
    }
}
