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
        
        
        $this->parameter('type', $value);
        
        return $this;

    }

    public function dataPlain(string $value): self {

        
        $this->parameterStructured('data', 'data:asText', $value);
        
        return $this;

    }

    public function dataEncoded(string $value): self {
        
        
        $this->parameterStructured('data', 'data:asBase64', $value);
        
        return $this;

    }

}
