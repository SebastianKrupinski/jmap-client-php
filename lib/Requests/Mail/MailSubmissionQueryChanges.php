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
    protected string $_filterClass = MailSubmissionFilter::class;
    protected string $_sortClass = MailSubmissionSort::class;
    
    public function filter(): MailSubmissionFilter {
        return parent::filter();
    }

    public function sort(): MailSubmissionSort {
        return parent::sort();
    }
    
}
