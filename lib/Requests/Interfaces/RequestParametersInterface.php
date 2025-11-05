<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Interfaces;

/**
 * Interface for JMAP request parameters
 */
interface RequestParametersInterface
{
    /**
     * Bind the parameters to an anchor object
     *
     * This method establishes a reference between the parameters object
     * and an external anchor, allowing modifications to be reflected.
     *
     * @param object $anchor Reference to the anchor object
     * @return self For method chaining
     */
    public function bind(&$anchor): static;

}
