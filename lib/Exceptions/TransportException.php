<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Exceptions;

/**
 * Thrown when the underlying client fails to deliver a request (network error, malformed request, etc.)
 */
class TransportException extends \RuntimeException
{
}
