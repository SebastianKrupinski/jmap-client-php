<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Sieve;

use JmapClient\Responses\Response;

/**
 * SieveScript/validate response, error is null when the script is valid
 */
class ScriptValidate extends Response
{
    public function valid(): bool
    {
        return $this->error() === null;
    }

    /**
     * SetError describing why the script is invalid, with type and description
     *
     * @return array{type?:string,description?:string}|null
     */
    public function error(): array|null
    {
        return $this->_response[self::RESPONSE_OBJECT]['error'] ?? null;
    }

    /**
     * Error type, for example invalidScript or blobNotFound
     */
    public function errorType(): string|null
    {
        return $this->error()['type'] ?? null;
    }

    public function errorDescription(): string|null
    {
        return $this->error()['description'] ?? null;
    }
}
