<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestParameters;

class MailPart extends RequestParameters
{
    protected object $_parameters;

    public function __construct(&$parameters)
    {
        $this->_parameters = & $parameters;
    }

    public function id(string $value): static
    {
        $this->_parameters->partId = $value;
        return $this;
    }

    public function blob(string $value): static
    {
        $this->_parameters->blobId = $value;
        return $this;
    }

    public function type(string $value): static
    {
        $this->_parameters->type = $value;
        return $this;
    }

    public function disposition(string $value): static
    {
        $this->_parameters->disposition = $value;
        return $this;
    }

    public function name(string $value): static
    {
        $this->_parameters->name = $value;
        return $this;
    }

    public function charset(string $value): static
    {
        $this->_parameters->charset = $value;
        return $this;
    }

    public function language(string $value): static
    {
        $this->_parameters->language = $value;
        return $this;
    }

    public function location(string $value): static
    {
        $this->_parameters->location = $value;
        return $this;
    }

    public function addPart(): MailPart
    {
        if (!isset($this->_parameters->subParts)) {
            $this->_parameters->subParts = [];
        }
        $this->_parameters->subParts[] = new \stdClass();
        return new MailPart(
            $this->_parameters->subParts[array_key_last($this->_parameters->subParts)]
        );
    }

    public function subParts(): MailPart
    {
        if (!isset($this->_parameters->subParts)) {
            $this->_parameters->subParts = [];
        }
        return $this->_parameters->subParts;
    }

}
