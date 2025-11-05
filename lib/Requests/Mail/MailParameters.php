<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestParameters;

class MailParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
    }

    public function in(string $value): static
    {
        $this->parameterStructured('mailboxIds', $value, true);
        return $this;
    }

    public function from(string $address, string $name = ''): static
    {
        $this->parameterCollection('from', (object) ['name' => $name, 'email' => $address]);
        return $this;
    }

    public function to(string $address, string $name = ''): static
    {
        $this->parameterCollection('to', (object) ['name' => $name, 'email' => $address]);
        return $this;
    }

    public function cc(string $address, string $name = ''): static
    {
        $this->parameterCollection('cc', (object) ['name' => $name, 'email' => $address]);
        return $this;
    }

    public function bcc(string $address, string $name = ''): static
    {
        $this->parameterCollection('bcc', (object) ['name' => $name, 'email' => $address]);
        return $this;
    }

    public function subject(string $value): static
    {
        $this->parameter('subject', $value);
        return $this;
    }

    public function contents(string $content, string $type, ?string $id = null): static
    {
        if (empty($id)) {
            $id = uniqid();
        }

        $this->parameterStructured('bodyStructure', 'partId', $id);
        $this->parameterStructured('bodyStructure', 'type', $id);
        $this->parameterStructured('bodyValues', $id, (object) ['value' => $content, 'isTruncated' => false]);

        return $this;
    }

    public function contentsPlain(string $content, string $id = null): static
    {
        return $this->contents($content, 'text/plain', $id);
    }

    public function contentsHtml(string $content, string $id = null): static
    {
        return $this->contents($content, 'text/html', $id);
    }

    public function structure(): MailPart
    {
        if (!isset($this->_parameters->bodyStructure)) {
            $this->parameter('bodyStructure', new \stdClass());
        }
        return new MailPart($this->_parameters->bodyStructure);
    }

    public function draft(): static
    {
        $this->parameterStructured('keywords', '$draft', true);
        return $this;
    }

    public function seen(): static
    {
        $this->parameterStructured('keywords', '$seen', true);
        return $this;
    }

    public function flagged(): static
    {
        $this->parameterStructured('keywords', '$flagged', true);
        return $this;
    }

    public function answered(): static
    {
        $this->parameterStructured('keywords', '$answered', true);
        return $this;
    }

    public function forwarded(): static
    {
        $this->parameterStructured('keywords', '$forwarded', true);
        return $this;
    }

}
