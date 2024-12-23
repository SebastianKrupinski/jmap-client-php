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
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestParse;

class MailParse extends RequestParse
{

    public function __construct(string $account, string $identifier = '', string $namespace = null, string $resource = null) {

        $space = $namespace ?? 'urn:ietf:params:jmap:mail';
        $class = $resource ?? 'Email';

        parent::__construct($space, $class, $account, $identifier);
        
    }

    public function property(string ...$value): self {

        if (!isset($this->_command['properties'])) {
            $this->_command['properties'] = [];
        }
        // creates or updates parameter and assigns value
        $this->_command['properties'] = array_merge($this->_command['properties'], $value);
        // return self for function chaining 
        return $this;

    }

    public function bodyProperty(string ...$value): self {

        if (!isset($this->_command['bodyProperties'])) {
            $this->_command['bodyProperties'] = [];
        }
        // creates or updates parameter and assigns value
        $this->_command['bodyProperties'] = array_merge($this->_command['bodyProperties'], $value);
        // return self for function chaining 
        return $this;

    }

    public function bodyText(bool $value): self {

        // creates or updates parameter and assigns value
        $this->_command['fetchTextBodyValues'] = $value;
        // return self for function chaining 
        return $this;

    }

    public function bodyHtml(bool $value): self {

        // creates or updates parameter and assigns value
        $this->_command['fetchHTMLBodyValues'] = $value;
        // return self for function chaining 
        return $this;
        
    }

    public function bodyAll(bool $value): self {

        // creates or updates parameter and assigns value
        $this->_command['fetchAllBodyValues'] = $value;
        // return self for function chaining 
        return $this;
        
    }

    public function bodyTruncate(int $value): self {

        // creates or updates parameter and assigns value
        $this->_command['maxBodyValueBytes'] = $value;
        // return self for function chaining 
        return $this;
        
    }

}