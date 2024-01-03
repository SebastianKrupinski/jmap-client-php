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

use JmapClient\Requests\RequestQuery;

class MailboxQuery extends RequestQuery
{

    public function __construct(string $account, string $identifier = '') {

        parent::__construct('urn:ietf:params:jmap:mail', 'Mailbox', $account, $identifier);
        
    }

    public function filter(): MailboxFilter {
        
        // return self for function chaining 
        return new MailboxFilter($this->_request);

    }

    public function sort(): MailboxSort {

        // return self for function chaining 
        return new MailboxSort($this->_request);

    }

    public function filterAsTree(bool $value): self {

        // creates or updates parameter and assigns value
        $this->_request[1]['filterAsTree'] = $value;
        // return self for function chaining 
        return $this;
        
    }

    public function sortAsTree(bool $value): self {

        // creates or updates parameter and assigns value
        $this->_request[1]['sortAsTree'] = $value;
        // return self for function chaining 
        return $this;
        
    }

}
