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

    public function headers(array $headers): static
    {
        foreach ($headers as $name => $value) {
            $this->parameter(sprintf('header:%s:asRaw', $name), $value);
        }
        return $this;
    }

    public function messageId(string $value): static
    {
        $this->parameterCollection('messageId', $value);
        return $this;
    }

    public function inReplyTo(string $value): static
    {
        $this->parameterCollection('inReplyTo', $value);
        return $this;
    }

    public function references(string $value): static
    {
        $this->parameterCollection('references', $value);
        return $this;
    }

    public function received(string $value): static
    {
        $this->parameter('receivedAt', $value);
        return $this;
    }

    public function sent(string $value): static
    {
        $this->parameter('sentAt', $value);
        return $this;
    }

    public function sender(string $address, string $name = ''): static
    {
        $this->parameterCollection('sender', (object) ['name' => $name, 'email' => $address]);
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

    public function replyTo(string $address, string $name = ''): static
    {
        $this->parameterCollection('replyTo', (object) ['name' => $name, 'email' => $address]);
        return $this;
    }

    public function subject(string $value): static
    {
        $this->parameter('subject', $value);
        return $this;
    }

    public function bodyText(string $content, string $type, ?string $id = null): static
    {
        if (empty($id)) {
            $id = uniqid();
        }

        $this->parameterStructured('bodyStructure', 'partId', $id);
        $this->parameterStructured('bodyStructure', 'type', $type);
        $this->bodyPartValue($id, $content);

        return $this;
    }

    public function bodyTextPlain(string $content, string $id = null): static
    {
        return $this->bodyText($content, 'text/plain', $id);
    }

    public function bodyTextHtml(string $content, string $id = null): static
    {
        return $this->bodyText($content, 'text/html', $id);
    }

    public function bodyPartStructure(): MailPart
    {
        if (!isset($this->_parameters->bodyStructure)) {
            $this->parameter('bodyStructure', new \stdClass());
        }
        return new MailPart($this->_parameters->bodyStructure);
    }

    public function bodyPartValue(string $id, string $value, bool $isTruncated = false, bool $isEncodingProblem = false): static
    {
        $this->parameterStructured('bodyValues', $id, (object) [
            'value' => $value,
            'isTruncated' => $isTruncated,
            'isEncodingProblem' => $isEncodingProblem,
        ]);
        return $this;
    }

    public function bodyPartValues(array $value): static
    {
        $this->parameter('bodyValues', (object) $value);
        return $this;
    }

    public function attachment(array $value): MailPart
    {
        $this->parameterCollection('attachments', (object) $value);

        return new MailPart($this->_parameters->attachments[array_key_last($this->_parameters->attachments)]);
    }

    public function keywords(array $keywords): static
    {
        foreach ($keywords as $name => $value) {
            $this->keyword($name, $value);
        }
        return $this;
    }

    public function keyword(string $name, bool $value = true): static
    {
        $this->parameterStructured('keywords', $name, $value);
        return $this;
    }

    public function draft(bool $value = true): static
    {
        return $this->keyword('$draft', $value);
    }

    public function seen(bool $value = true): static
    {
        return $this->keyword('$seen', $value);
    }

    public function flagged(bool $value = true): static
    {
        return $this->keyword('$flagged', $value);
    }

    public function answered(bool $value = true): static
    {
        return $this->keyword('$answered', $value);
    }

    public function forwarded(bool $value = true): static
    {
        return $this->keyword('$forwarded', $value);
    }

    public function phishing(bool $value = true): static
    {
        return $this->keyword('$phishing', $value);
    }

    public function junk(bool $value = true): static
    {
        return $this->keyword('$junk', $value);
    }

    public function notjunk(bool $value = true): static
    {
        return $this->keyword('$notjunk', $value);
    }
}
