<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Sieve;

use JmapClient\Requests\RequestQuery;

class ScriptQuery extends RequestQuery
{
    protected string $_space = 'urn:ietf:params:jmap:sieve';
    protected string $_class = 'SieveScript';
    protected string $_filterClass = ScriptFilter::class;
    protected string $_sortClass = ScriptSort::class;

    public function filter(): ScriptFilter
    {
        return parent::filter();
    }

    public function sort(): ScriptSort
    {
        return parent::sort();
    }
}
