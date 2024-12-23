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

class MailSubmissionQuery extends RequestQuery
{

    public function __construct(string $account, string $identifier = '', string $namespace = null, string $resource = null) {

        $space = $namespace ?? 'urn:ietf:params:jmap:submission';
        $class = $resource ?? 'EmailSubmission';

        parent::__construct($space, $class, $account, $identifier);
        
    }

    public function filter(): MailSubmissionFilter {

        // evaluate if filter paramater exist and create if needed
        if (!isset($this->_command['filter'])) {
            $this->_command['filter'] = new \stdClass();
        }
        // return self for function chaining 
        return new MailSubmissionFilter($this->_command['filter']);

    }

    public function sort(): MailSubmissionSort {

        // evaluate if sort paramater exist and create if needed
        if (!isset($this->_command['sort'])) {
            $this->_command['sort'] = [];
        }
        // return self for function chaining 
        return new MailSubmissionSort($this->_command['sort']);

    }

}
