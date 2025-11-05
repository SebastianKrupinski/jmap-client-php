<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestGet;

class MailGet extends RequestGet
{
    protected string $_space = 'urn:ietf:params:jmap:mail';
    protected string $_class = 'Email';

    public function property(string ...$value): static
    {
        if (!isset($this->_command['properties'])) {
            $this->_command['properties'] = [];
        }
        $this->_command['properties'] = array_merge($this->_command['properties'], $value);

        return $this;
    }

    public function bodyProperty(string ...$value): static
    {
        if (!isset($this->_command['bodyProperties'])) {
            $this->_command['bodyProperties'] = [];
        }
        $this->_command['bodyProperties'] = array_merge($this->_command['bodyProperties'], $value);

        return $this;
    }

    public function bodyText(bool $value): static
    {
        $this->_command['fetchTextBodyValues'] = $value;
        return $this;
    }

    public function bodyHtml(bool $value): static
    {
        $this->_command['fetchHTMLBodyValues'] = $value;
        return $this;
    }

    public function bodyAll(bool $value): static
    {
        $this->_command['fetchAllBodyValues'] = $value;
        return $this;
    }

    public function bodyTruncate(int $value): static
    {
        $this->_command['maxBodyValueBytes'] = $value;
        return $this;
    }

}
