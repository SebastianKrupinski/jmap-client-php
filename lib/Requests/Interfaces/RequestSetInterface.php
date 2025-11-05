<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Interfaces;

/**
 * Interface for JMAP Set operations
 *
 * @template TParameters of RequestParametersInterface
 */
interface RequestSetInterface
{
    /**
     * Set the if-in-state condition for conditional updates
     *
     * @param string $state The state value to match
     *
     * @return self
     */
    public function state(string $state): static;

    /**
     * Create a new object
     *
     * @param string $id Object identifier for creation
     * @param TParameters|null $object Optional parameters object
     *
     * @return TParameters The parameters object for method chaining
     */
    public function create(string $id, mixed $object = null): RequestParametersInterface;
}
