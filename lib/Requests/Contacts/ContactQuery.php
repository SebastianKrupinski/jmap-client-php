<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Contacts;

use DateTimeZone;
use JmapClient\Requests\RequestQuery;

class ContactQuery extends RequestQuery {

    protected string $_space = 'urn:ietf:params:jmap:contacts';
    protected string $_class = 'ContactCard';

    public function filter(): ContactFilter {
        
        // evaluate if filter parameter exist and create if needed
        if (!isset($this->_command['filter'])) {
            $this->_command['filter'] = new \stdClass();
        }
        // return self for function chaining 
        return new ContactFilter($this->_command['filter']);

    }

    public function sort(): ContactSort {

        // evaluate if sort parameter exist and create if needed
        if (!isset($this->_command['sort'])) {
            $this->_command['sort'] = [];
        }
        // return self for function chaining 
        return new ContactSort($this->_command['sort']);

    }

    public function timezone(DateTimeZone $value): self {

        // creates or updates parameter and assigns new value
        $this->_request[1]['timeZone'] = $value->getName();
        // return self for function chaining 
        return $this;
        
    }

}
