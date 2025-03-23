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
namespace JmapClient\Requests;

class Request implements \JsonSerializable
{
    public const DATE_FORMAT_LOCAL = 'Y-m-d\TH:i:s';
    public const DATE_FORMAT_UTC = 'Y-m-d\TH:i:s\Z';

    protected string $_space;
    protected string $_class;
    protected string $_method;
    protected string $_account;
    protected string $_identifier;
    protected array $_command;
    protected array $_request = ['/', [], ''];

    public function __construct (?string $account = null, ?string $identifier = null, ?string $space = null, ?string $class = null, ?string $method = null) {

        if ($account !== null) {
            $this->_account = $account;
        }
        if ($identifier !== null) {
            $this->_identifier = $identifier;
        }
        if ($space !== null) {
            $this->_space = $space;
        }
        if ($class !== null) {
            $this->_class = $class;
        }
        if ($method !== null) {
            $this->_method = $method;
        }
        if ($this->_account === null) {
            $this->_account = 'default';
        }
        if (!isset($this->_identifier)) {
            $this->_identifier = uniqid();
        }
        if (!isset($this->_space)) {
            $this->_space = 'core';
        }
        if (!isset($this->_class)) {
            $this->_class = 'Core';
        }
        if (!isset($this->_method)) {
            $this->_method = 'echo';
        }
        $this->_request[0] = $this->_class . '/' . $this->_method;
        $this->_request[1] = ['accountId' => $this->_account];
        $this->_request[2] = $this->_identifier;

        $this->_command =& $this->_request[1];
        
    }

    public function getIdentifier(): string {
        return $this->_identifier;
    }

    public function setIdentifier(string $identifier): void {
        $this->_identifier = $identifier;
        $this->_request[2] = $identifier;
    }

    public function getAccount(): string {
        return $this->_account;
    }

    public function setAccount(string $account): void {
        $this->_account = $account;
        $this->_command['accountId'] = $account;
    }

    public function getNamespace(): string {
        return $this->_space;
    }

    public function setNamespace(string $space): void {
        $this->_space = $space;
    }

    public function getClass(): string {
        return $this->_class;
    }

    public function setClass(string $class): void {
        $this->_class = $class;
        $this->_request[0] = $class . '/' . $this->_method;
    }

    public function getMethod(): string {
        return $this->_method;
    }

    public function setMethod(string $method): void {
        $this->_method = $method;
        $this->_request[0] = $this->_class . '/' . $method;
    }

    public function toJson(int $flags = 0): string {
        return json_encode($this->_request, JSON_UNESCAPED_SLASHES | $flags);
    }

    public function jsonSerialize(): mixed {
        return $this->_request;
    }

}
