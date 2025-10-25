<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestQuery;

class MailQuery extends RequestQuery {

    protected string $_space = 'urn:ietf:params:jmap:mail';
    protected string $_class = 'Email';

    public function filter(): MailFilter {
        if (!isset($this->_command['filter'])) {
            $this->_command['filter'] = new \stdClass();
        }
        return new MailFilter($this->_command['filter']);
    }

    public function sort(): MailSort {
        if (!isset($this->_command['sort'])) {
            $this->_command['sort'] = [];
        }
        return new MailSort($this->_command['sort']);
    }
    
    public function collapseThreads(bool $value): self {
        $this->_command['collapseThreads'] = $value;
        return $this;
    }

}
