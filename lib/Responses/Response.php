<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses;

class Response {

    public const RESPONSE_OPERATION = 0;
    public const RESPONSE_OBJECT = 1;
    public const RESPONSE_IDENTIFIER = 2;

    protected string $_class = '';
    protected string $_method = '';
    protected string $_account = '';
    protected string $_identifier = '';
    protected array $_response = [];

    public function __construct (array $response = []) {

        if (!empty($response)) {
            $this->fromData($response);
        }

    }

    public function identifier(): string {
        return $this->_identifier;
    }

    public function class(): string {
        return $this->_class;
    }

    public function method(): string {
        return $this->_method;
    }

    public function account(): string {
        return $this->_account;
    }

    public function fromData(array $response): void {
        
        $this->_response = $response;
        [$this->_class, $this->_method] = explode('/', $this->_response[self::RESPONSE_OPERATION]);
        $this->_account = (isset($this->_response[self::RESPONSE_OBJECT]['accountId'])) ? $this->_response[self::RESPONSE_OBJECT]['accountId'] : '';
        $this->_identifier = (isset($this->_response[self::RESPONSE_IDENTIFIER])) ? $this->_response[self::RESPONSE_IDENTIFIER] : '';
    
    }

    public function fromJson(string $json, int $options = 0): void {
        $this->fromData(json_decode($json, true, 512, JSON_THROW_ON_ERROR | $options));
    }

}
