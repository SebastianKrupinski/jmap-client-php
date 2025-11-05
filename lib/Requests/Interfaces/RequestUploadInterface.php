<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Interfaces;

/**
 * Interface for JMAP Upload operations
 *
 * @template TParameters of RequestParametersInterface
 */
interface RequestUploadInterface
{
    /**
     * Create a new upload object
     *
     * @param string $id Object identifier for creation
     * @param TParameters|null $object Optional parameters object
     *
     * @return TParameters The parameters object for method chaining
     */
    public function create(string $id, mixed $object = null): RequestParametersInterface;
}
