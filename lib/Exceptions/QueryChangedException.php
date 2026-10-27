<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Exceptions;

/**
 * Thrown when the query state changes between pages, the retrieved pages no longer form one consistent result
 */
class QueryChangedException extends \RuntimeException
{
    public function __construct(
        private string $expectedState,
        private string $actualState,
        ?\Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf('Query state changed from %s to %s while paging, retry the operation', $expectedState, $actualState),
            0,
            $previous,
        );
    }

    public function getExpectedState(): string
    {
        return $this->expectedState;
    }

    public function getActualState(): string
    {
        return $this->actualState;
    }
}
