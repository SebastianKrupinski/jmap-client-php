<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses;

class ResponseException
{
    public const RESPONSE_OPERATION = 0;
    public const RESPONSE_OBJECT = 1;
    public const RESPONSE_IDENTIFIER = 2;

    protected array $_response = [];

    public function __construct(array $response = [])
    {
        $this->_response = $response;
    }

    public function identifier(): string
    {
        return isset($this->_response[self::RESPONSE_IDENTIFIER]) ? $this->_response[self::RESPONSE_IDENTIFIER] : '';
    }

    public function type(): string
    {
        return isset($this->_response[self::RESPONSE_OBJECT]['type']) ? $this->_response[self::RESPONSE_OBJECT]['type'] : '';
    }

    public function description(): string
    {
        return isset($this->_response[self::RESPONSE_OBJECT]['description']) ? $this->_response[self::RESPONSE_OBJECT]['description'] : '';
    }
}
