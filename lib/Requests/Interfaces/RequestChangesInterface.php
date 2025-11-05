<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Interfaces;

/**
 * Interface for JMAP Changes operations
 */
interface RequestChangesInterface
{
    /**
     * Set the state to compare changes from
     *
     * @param string $value The state value
     * @return self
     */
    public function state(string $value): static;

    /**
     * Set maximum number of changes to return
     *
     * @param int $value Maximum changes count
     * @return self
     */
    public function limitRelative(int $value): static;
}
