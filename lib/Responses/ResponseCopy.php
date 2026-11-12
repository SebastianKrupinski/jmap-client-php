<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses;

/**
 * Standard Foo/copy response (RFC 8620 Section 5.4)
 *
 * Shares the created / notCreated structure of a Foo/set response
 */
class ResponseCopy extends ResponseSet
{
    public function sourceAccount(): string
    {
        return $this->_response[self::RESPONSE_OBJECT]['fromAccountId'] ?? '';
    }
}
