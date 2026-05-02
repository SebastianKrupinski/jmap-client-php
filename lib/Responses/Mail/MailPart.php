<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Mail;

class MailPart
{
    protected array $_part = [];
    protected array $_subparts = [];

    public function __construct(array $part = [])
    {
        if (isset($part['subParts'])) {
            foreach ($part['subParts'] as $entry) {
                $this->_subparts[] = new MailPart($entry);
            }
            unset($part['subParts']);
        }
        $this->_part = $part;
    }

    public function id(): string|null
    {
        return $this->_part['partId'] ?? null;
    }

    public function blob(): string|null
    {
        return $this->_part['blobId'] ?? null;
    }

    public function size(): int|null
    {
        return $this->_part['size'] ?? null;
    }

    public function headers(): array|null
    {
        return $this->_part['headers'] ?? null;
    }

    public function name(): string|null
    {
        return $this->_part['name'] ?? null;
    }

    public function type(): string|null
    {
        return $this->_part['type'] ?? null;
    }

    public function disposition(): string|null
    {
        return $this->_part['disposition'] ?? null;
    }

    public function cid(): string|null
    {
        return $this->_part['cid'] ?? null;
    }

    public function charset(): string|null
    {
        return $this->_part['charset'] ?? null;
    }

    public function language(): array|null
    {
        return $this->_part['language'] ?? null;
    }

    public function location(): string|null
    {
        return $this->_part['location'] ?? null;
    }

    public function parts(): array|null
    {
        return $this->_subparts;
    }
}
