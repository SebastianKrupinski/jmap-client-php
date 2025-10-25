<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestQuery;

class MailboxQuery extends RequestQuery {

    protected string $_space = 'urn:ietf:params:jmap:mail';
    protected string $_class = 'Mailbox';

    public function filter(): MailboxFilter {
        
        // evaluate if filter parameter exist and create if needed
        if (!isset($this->_command['filter'])) {
            $this->_command['filter'] = new \stdClass();
        }
        // return self for function chaining 
        return new MailboxFilter($this->_command['filter']);

    }

    public function sort(): MailboxSort {

        // evaluate if sort parameter exist and create if needed
        if (!isset($this->_command['sort'])) {
            $this->_command['sort'] = [];
        }
        // return self for function chaining 
        return new MailboxSort($this->_command['sort']);

    }

    public function filterAsTree(bool $value): self {

        // creates or updates parameter and assigns value
        $this->_command['filterAsTree'] = $value;
        // return self for function chaining 
        return $this;
        
    }

    public function sortAsTree(bool $value): self {

        // creates or updates parameter and assigns value
        $this->_command['sortAsTree'] = $value;
        // return self for function chaining 
        return $this;
        
    }

}
