<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Quota;

use JmapClient\Requests\RequestChanges;

class QuotaChanges extends RequestChanges
{
    protected string $_space = 'urn:ietf:params:jmap:quota';
    protected string $_class = 'Quota';
}
