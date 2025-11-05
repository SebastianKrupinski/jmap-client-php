<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Interfaces;

use JmapClient\Requests\Request;

/**
 * Interface for JMAP Get operations
 */
interface RequestGetInterface
{
    /**
     * Set target object IDs
     *
     * @param string ...$id One or more object identifiers
     * @return self
     */
    public function target(string ...$id): static;

    /**
     * Set target IDs from another request result
     *
     * @param Request $request The request to reference
     * @param string $selector The path to extract IDs from
     * @return self
     */
    public function targetFromRequest(Request $request, string $selector): static;

    /**
     * Set which properties to fetch
     *
     * @param string ...$name One or more property names
     * @return self
     */
    public function property(string ...$name): static;
}
