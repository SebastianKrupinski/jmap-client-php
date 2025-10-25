<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Blob;

use JmapClient\Requests\RequestParameters;

class BlobParameters extends RequestParameters
{
    public function __construct(&$parameters = null) {

        parent::__construct($parameters);

    }

    public function type(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('type', $value);
        // return self for function chaining
        return $this;

    }

    public function dataPlain(string $value): self {

        // creates or updates parameter and assigns value
        $this->parameterStructured('data', 'data:asText', $value);
        // return self for function chaining
        return $this;

    }

    public function dataEncoded(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('data', 'data:asBase64', $value);
        // return self for function chaining
        return $this;

    }

}
