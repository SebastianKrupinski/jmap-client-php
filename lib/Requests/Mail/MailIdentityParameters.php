<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestParameters;

class MailIdentityParameters extends RequestParameters
{
    public function __construct(&$parameters = null) {

        parent::__construct($parameters);

    }

    public function name(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('name', $value);
        // return self for function chaining
        return $this;

    }

    public function replyTo(string $address, string $name = ''): self {
        
        // creates or updates parameter and assigns value
        $this->parameterCollection('replyTo', (object) ['name' => $name, 'email' => $address]);
        // return self for function chaining
        return $this;

    }

    public function bcc(string $address, string $name = ''): self {
        
        // creates or updates parameter and assigns value
        $this->parameterCollection('bcc', (object) ['name' => $name, 'email' => $address]);
        // return self for function chaining
        return $this;

    }

    public function signature(string $value, string $type): self {
        
        match ($type) {
            'plain' => $this->parameter('textSignature', $value),
            'html' => $this->parameter('htmlSignature', $value),
        };
        // return self for function chaining
        return $this;

    }

    public function signaturePlain(string $value): self {
        
        return $this->parameter('textSignature', $value);

    }

    public function signatureHtml(string $value): self {
        
        return $this->parameter('htmlSignature', $value);

    }

}
