<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestQueryChanges;

class MailQueryChanges extends RequestQueryChanges {

    protected string $_space = 'urn:ietf:params:jmap:mail';
    protected string $_class = 'Email';
    
    public function filter(): MailFilter {

        // evaluate if filter paramater exist and create if needed
        if (!isset($this->_command['filter'])) {
            $this->_command['filter'] = new \stdClass();
        }
        // return self for function chaining 
        return new MailFilter($this->_command['filter']);

    }

    public function sort(): MailSort {

        // evaluate if sort paramater exist and create if needed
        if (!isset($this->_command['sort'])) {
            $this->_command['sort'] = [];
        }
        // return self for function chaining 
        return new MailSort($this->_command['sort']);

    }
    
    public function collapseThreads(bool $value): self {

        // creates or updates parameter and assigns value
        $this->_command['collapseThreads'] = $value;
        // return self for function chaining 
        return $this;
        
    }

}
