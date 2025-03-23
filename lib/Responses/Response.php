<?php
declare(strict_types=1);

/**
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
*
* @author Sebastian Krupinski <krupinski01@gmail.com>
*
* @license AGPL-3.0-or-later
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
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
