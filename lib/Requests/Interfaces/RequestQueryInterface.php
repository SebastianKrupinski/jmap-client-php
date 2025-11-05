<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Interfaces;

use JmapClient\Requests\RequestFilter;
use JmapClient\Requests\RequestSort;

/**
 * Interface for JMAP Query operations
 *
 * @template TFilter of RequestFilter
 * @template TSort of RequestSort
 */
interface RequestQueryInterface
{
    /**
     * Get filter builder
     *
     * @return TFilter The filter object for building queries
     */
    public function filter(): RequestFilter;

    /**
     * Get sort builder
     *
     * @return TSort The sort object for building sort criteria
     */
    public function sort(): RequestSort;

    /**
     * Set absolute position and count limits
     *
     * @param int|null $position Starting position
     * @param int|null $count Maximum number of results
     *
     * @return self
     */
    public function limitAbsolute(?int $position = null, ?int $count = null): static;

    /**
     * Set relative position and count limits with anchor
     *
     * @param int|null $anchor Anchor position
     * @param int|null $count Maximum number of results
     * @param int|null $offset Offset from anchor
     *
     * @return self
     */
    public function limitRelative(?int $anchor = null, ?int $count = null, ?int $offset = null): static;

    /**
     * Enable or disable total count calculation
     *
     * @param bool $value Whether to calculate total
     *
     * @return self
     */
    public function tally(bool $value): static;
}
