<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
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
        return $this->_part['partId'];
    }

    public function blob(): string|null
    {
        return $this->_part['blobId'];
    }

    public function disposition(): string|null
    {
        return $this->_part['disposition'];
    }

    public function type(): string|null
    {
        return $this->_part['type'];
    }

    public function charset(): string|null
    {
        return $this->_part['charset'];
    }

    public function name(): string|null
    {
        return $this->_part['name'];
    }

    public function location(): string|null
    {
        return $this->_part['location'];
    }

    public function cid(): string|null
    {
        return $this->_part['cid'];
    }

    public function size(): int|null
    {
        return $this->_part['size'];
    }

    public function imageSize(): int|null
    {
        return $this->_part['imageSize'];
    }

    public function parts(): array|null
    {
        return $this->_subparts;
    }
}
