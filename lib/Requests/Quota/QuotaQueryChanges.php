<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Quota;

use JmapClient\Requests\RequestQueryChanges;

class QuotaQueryChanges extends RequestQueryChanges
{
    protected string $_space = 'urn:ietf:params:jmap:quota';
    protected string $_class = 'Quota';
    protected string $_filterClass = QuotaFilter::class;
    protected string $_sortClass = QuotaSort::class;

    public function filter(): QuotaFilter
    {
        return parent::filter();
    }

    public function sort(): QuotaSort
    {
        return parent::sort();
    }
}
