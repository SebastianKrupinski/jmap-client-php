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
    protected string $_filterClass = MailFilter::class;
    protected string $_sortClass = MailSort::class;
    
    public function filter(): MailFilter {
        return parent::filter();
    }

    public function sort(): MailSort {
        return parent::sort();
    }
    
    public function collapseThreads(bool $value): self {

        // creates or updates parameter and assigns value
        $this->_command['collapseThreads'] = $value;
        // return self for function chaining 
        return $this;
        
    }

}
