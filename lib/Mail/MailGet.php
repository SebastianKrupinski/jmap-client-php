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
namespace JmapClient\Mail;

use JmapClient\Base\RequestGet;

class MailGet extends RequestGet
{

    public function __construct(string $account, string $identifier = '') {

        parent::__construct('urn:ietf:params:jmap:mail', 'Email', 'get', $account, $identifier);
        
    }

    public function bodyProperty(string $value): MailGet {

        // creates or updates parameter and assigns value
        $this->_request[1]['bodyProperties'][] = $value;
        // return self for function chaining 
        return $this;

    }

    public function bodyText(bool $value): MailGet {

        // creates or updates parameter and assigns value
        $this->_request[1]['fetchTextBodyValues'] = $value;
        // return self for function chaining 
        return $this;

    }

    public function bodyHtml(bool $value): MailGet {

        // creates or updates parameter and assigns value
        $this->_request[1]['fetchHTMLBodyValues'] = $value;
        // return self for function chaining 
        return $this;
        
    }

    public function bodyAll(bool $value): MailGet {

        // creates or updates parameter and assigns value
        $this->_request[1]['fetchAllBodyValues'] = $value;
        // return self for function chaining 
        return $this;
        
    }

    public function bodyTruncate(int $value): MailGet {

        // creates or updates parameter and assigns value
        $this->_request[1]['maxBodyValueBytes'] = $value;
        // return self for function chaining 
        return $this;
        
    }

}
