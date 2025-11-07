<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Blob;

use JmapClient\Requests\RequestUpload;

/**
 * @extends RequestUpload<BlobParameters>
 */
class BlobSet extends RequestUpload
{
    protected string $_space = 'urn:ietf:params:jmap:Blob';
    protected string $_class = 'Blob';
    protected string $_parametersClass = BlobParameters::class;

    /**
     * Create a blob
     *
     * @param string $id Blob identifier
     * @param BlobParameters|null $object Blob parameters object
     * @return BlobParameters The blob parameters for method chaining
     */
    public function create(string $id, mixed $object = null): BlobParameters
    {
        return parent::create($id, $object);
    }
}
