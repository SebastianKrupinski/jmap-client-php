<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Sieve;

use JmapClient\Requests\RequestGet;

class ScriptGet extends RequestGet
{
    protected string $_space = 'urn:ietf:params:jmap:sieve';
    protected string $_class = 'SieveScript';
}
