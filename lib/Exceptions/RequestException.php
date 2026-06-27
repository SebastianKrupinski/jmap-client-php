<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Exceptions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Thrown when a request completes but the server responds with an error status code (>= 400)
 */
class RequestException extends \RuntimeException
{
    public function __construct(
        private RequestInterface $request,
        private ResponseInterface $response,
        ?\Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf(
                'Request to %s failed with status %d %s',
                (string)$request->getUri(),
                $response->getStatusCode(),
                $response->getReasonPhrase(),
            ),
            $response->getStatusCode(),
            $previous,
        );
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
