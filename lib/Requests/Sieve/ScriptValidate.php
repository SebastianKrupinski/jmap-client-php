<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Sieve;

use JmapClient\Requests\Request;

/**
 * SieveScript/validate, checks a script for errors without storing it
 */
class ScriptValidate extends Request
{
    protected string $_space = 'urn:ietf:params:jmap:sieve';
    protected string $_class = 'SieveScript';
    protected string $_method = 'validate';

    /**
     * Blob holding the script content to validate
     */
    public function blob(string $value): static
    {
        $this->_command['blobId'] = $value;
        return $this;
    }
}
