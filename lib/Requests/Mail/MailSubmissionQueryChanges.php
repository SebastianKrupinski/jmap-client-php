<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestQueryChanges;

class MailSubmissionQueryChanges extends RequestQueryChanges {

    protected string $_space = 'urn:ietf:params:jmap:submission';
    protected string $_class = 'EmailSubmission';
    
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
