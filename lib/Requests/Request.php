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
    protected string $_space = '';
    protected string $_class = '';
    protected string $_method = '';
    protected string $_account = '';
    protected string $_identifier = '';
    protected array $_command;
    protected array $_request = [];

    public function __construct (string $space, string $class, string $method, string $account, string $identifier = '') {

        $this->_space = $space;
        $this->_class = $class;
        $this->_method = $method;
        $this->_account = $account;
        $this->_identifier = (!empty($identifier)) ? $identifier : uniqid();
        $this->_request[0] = $this->_class . '/' . $this->_method;
        $this->_request[1] = ['accountId' => $this->_account];
        $this->_request[2] = $this->_identifier;

        $this->_command =& $this->_request[1];
        
    }

    public function identifier() {
        return $this->_identifier;
    }

    public function namespace() {
        return $this->_space;
    }

    public function class() {
        return $this->_class;
    }

    public function method() {
        return $this->_method;
    }

    public function jsonSerialize() {
        return $this->_request;
    }

}
