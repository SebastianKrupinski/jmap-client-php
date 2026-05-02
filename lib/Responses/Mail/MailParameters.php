<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Mail;

use JmapClient\Responses\ResponseParameters;

class MailParameters extends ResponseParameters
{
    protected MailPart $_structure;
    protected array $_attachments = [];

    public function __construct(array $response = [])
    {
        if (isset($response['bodyStructure'])) {
            $this->_structure = new MailPart($response['bodyStructure']);
        }

        if (isset($response['attachments']) && is_array($response['attachments'])) {
            $this->_attachments = array_map(static fn (array $part): MailPart => new MailPart($part), $response['attachments']);
        }

        parent::__construct($response);
    }

    public function in(): array
    {
        return array_keys($this->parameter('mailboxIds')) ?? [];
    }

    public function id(): string|null
    {
        return $this->parameter('id');
    }

    public function thread(): string|null
    {
        return $this->parameter('threadId');
    }

    public function blob(): string|null
    {
        return $this->parameter('blobId');
    }

    public function size(): int|null
    {
        return $this->parameter('size');
    }

    public function header(string $name, string $form = 'asRaw', bool $all = false): mixed
    {
        $property = sprintf('header:%s:%s', $name, $form);
        if ($all) {
            $property .= ':all';
        }

        return $this->parameter($property);
    }

    public function messageId(): array|null
    {
        return $this->parameter('messageId');
    }

    public function inReplyTo(): array|null
    {
        return $this->parameter('inReplyTo');
    }

    public function references(): array|null
    {
        return $this->parameter('references');
    }

    public function received(): string|null
    {
        return $this->parameter('receivedAt');
    }

    public function sent(): string|null
    {
        return $this->parameter('sentAt');
    }

    public function sender(): array
    {
        return $this->parameter('sender') ?? [];
    }

    public function from(): array|null
    {
        return $this->parameter('from');
    }

    public function to(): array|null
    {
        return $this->parameter('to');
    }

    public function cc(): array|null
    {
        return $this->parameter('cc');
    }

    public function bcc(): array|null
    {
        return $this->parameter('bcc');
    }

    public function replyTo(): array|null
    {
        return $this->parameter('replyTo');
    }

    public function subject(): string|null
    {
        return $this->parameter('subject');
    }

    public function bodyText(): string|null
    {
        $value = $this->parameter('htmlBody') ?? $this->parameter('textBody');
        if (is_string($value)) {
            return $value;
        }

        $texts = $this->extractText($this->bodyPartStructure(), $this->bodyPartValues());

        return $texts['textHtml'] ?? $texts['textPlain'] ?? null;
    }

    public function bodyTextHtml(): string|null
    {
        $value = $this->parameter('htmlBody');
        if (is_string($value)) {
            return $value;
        }

        $texts = $this->extractText($this->bodyPartStructure(), $this->bodyPartValues());

        return $texts['textHtml'] ?? null;
    }

    public function bodyTextPlain(): string|null
    {
        $value = $this->parameter('textBody');
        if (is_string($value)) {
            return $value;
        }

        $texts = $this->extractText($this->bodyPartStructure(), $this->bodyPartValues());

        return $texts['textPlain'] ?? null;
    }

    public function bodyTextPreview(): string|null
    {
        return $this->parameter('preview');
    }

    public function bodyPartStructure(): MailPart|null
    {
        if (!isset($this->_structure) && is_array($this->parameter('bodyStructure'))) {
            $this->_structure = new MailPart($this->parameter('bodyStructure'));
        }

        return $this->_structure ?? null;
    }

    public function bodyPartValue(string $partId): string|null
    {
        $bodyValues = $this->bodyPartValues();

        return isset($bodyValues[$partId]['value']) && is_string($bodyValues[$partId]['value'])
            ? $bodyValues[$partId]['value']
            : null;
    }

    public function bodyPartValues(): array
    {
        return (array) ($this->parameter('bodyValues') ?? []);
    }

    public function attachments(): array
    {
        return $this->_attachments;
    }

    public function hasAttachment(): bool|null
    {
        return $this->parameter('hasAttachment');
    }

    public function keywords(): array
    {
        return (array) ($this->parameter('keywords') ?? []);
    }

    public function keyword(string $name): bool|null
    {
        $keywords = $this->keywords();
        return array_key_exists($name, $keywords) ? $keywords[$name] : null;
    }

    public function draft(): bool|null
    {
        return $this->keyword('$draft');
    }

    public function seen(): bool|null
    {
        return $this->keyword('$seen');
    }

    public function flagged(): bool|null
    {
        return $this->keyword('$flagged');
    }

    public function answered(): bool|null
    {
        return $this->keyword('$answered');
    }

    public function forwarded(): bool|null
    {
        return $this->keyword('$forwarded');
    }

    public function phishing(): bool|null
    {
        return $this->keyword('$phishing');
    }

    public function junk(): bool|null
    {
        return $this->keyword('$junk');
    }

    public function notjunk(): bool|null
    {
        return $this->keyword('$notjunk');
    }

    private function extractText(MailPart|null $structure, array $bodyValues): array
    {
        $body = [
            'textHtml' => null,
            'textPlain' => null,
        ];

        if ($structure === null || $bodyValues === []) {
            return $body;
        }

        $partId = $structure->id();
        $value = is_string($partId) ? ($bodyValues[$partId]['value'] ?? null) : null;

        if ($structure->type() === 'text/html' && is_string($value)) {
            $body['textHtml'] = $value;
        }

        if ($structure->type() === 'text/plain' && is_string($value)) {
            $body['textPlain'] = $value;
        }

        foreach ($structure->parts() ?? [] as $part) {
            $values = $this->extractText($part, $bodyValues);
            $body['textHtml'] ??= $values['textHtml'];
            $body['textPlain'] ??= $values['textPlain'];
        }

        return $body;
    }
}
