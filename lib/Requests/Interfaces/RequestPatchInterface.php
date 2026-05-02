<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Interfaces;

/**
 * Interface for JMAP patch request parameters
 */
interface RequestPatchInterface
{
    /**
     * Bind the patch to an anchor object
     *
     * @param object $anchor Reference to the anchor object
     * @return self For method chaining
     */
    public function bind(&$anchor): static;

    /**
     * Set a JMAP patch path to a value
     *
     * @param string $path The JMAP patch path
     * @param mixed $value The value for the path
     *
     * @return self For method chaining
     */
    public function path(string $path, mixed $value): static;

    /**
     * Replace the patch object with raw patch data
     *
     * @param array<mixed> $value Patch data keyed by JMAP patch path
     *
     * @return self For method chaining
     */
    public function parametersRaw(array $value): static;
}
