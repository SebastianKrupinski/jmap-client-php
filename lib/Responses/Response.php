<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses;

class Response
{
    public const RESPONSE_OPERATION = 0;
    public const RESPONSE_OBJECT = 1;
    public const RESPONSE_IDENTIFIER = 2;

    protected string $_class = '';
    protected string $_method = '';
    protected string $_account = '';
    protected string $_identifier = '';
    protected array $_response = [];

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->jsonDeserialize($data);
        }
    }

    public function jsonDeserialize(array $data): static
    {
        $this->_response = $data;
        [$this->_class, $this->_method] = explode('/', $this->_response[self::RESPONSE_OPERATION]);
        $this->_account = (isset($this->_response[self::RESPONSE_OBJECT]['accountId'])) ? $this->_response[self::RESPONSE_OBJECT]['accountId'] : '';
        $this->_identifier = (isset($this->_response[self::RESPONSE_IDENTIFIER])) ? $this->_response[self::RESPONSE_IDENTIFIER] : '';

        return $this;
    }

    public function jsonDecode(string $data, int $options = 0): static
    {
        return $this->jsonDeserialize(json_decode($data, true, 512, JSON_THROW_ON_ERROR | $options));
    }

    public function identifier(): string
    {
        return $this->_identifier;
    }

    public function class(): string
    {
        return $this->_class;
    }

    public function method(): string
    {
        return $this->_method;
    }

    public function account(): string
    {
        return $this->_account;
    }
}
