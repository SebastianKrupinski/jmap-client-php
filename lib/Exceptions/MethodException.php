<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Exceptions;

use JmapClient\Responses\ResponseException;

/**
 * Thrown when the server answers a method call with a method level error (RFC 8620 section 3.6.2)
 */
class MethodException extends \RuntimeException
{
    public function __construct(
        private ResponseException $response,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($response->type() . ': ' . $response->description(), 0, $previous);
    }

    public function getResponse(): ResponseException
    {
        return $this->response;
    }

    /**
     * Error type, one of the ResponseException constants
     */
    public function getType(): string
    {
        return $this->response->type();
    }
}
