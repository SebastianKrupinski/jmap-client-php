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

use JmapClient\Requests\RequestSet;
use JmapClient\Requests\Mail\MailParameters;

class MailSet extends RequestSet
{

    public function __construct(string $account, string $identifier = '') {

        parent::__construct('urn:ietf:params:jmap:mail', 'Email', $account, $identifier);
        
    }

    public function create(string $id): MailParameters {
        
        // evaluate if create paramater exist and create if needed
        if (!isset($this->_command['create'][$id])) {
            $this->_command['create'][$id] = new \stdClass();
        }
        // return self for function chaining 
        return new MailParameters($this->_command['create'][$id]);

    }

    public function update(string $id): MailParameters {
        
        // evaluate if create paramater exist and create if needed
        if (!isset($this->_command['update'][$id])) {
            $this->_command['update'][$id] = new \stdClass();
        }
        // return self for function chaining 
        return new MailParameters($this->_command['update'][$id]);

    }

    public function delete(string $id): self {

        // creates or updates parameter and assigns new value
        $this->_command['destroy'][] = $id;
        // return self for function chaining 
        return $this;
        
    }

}
