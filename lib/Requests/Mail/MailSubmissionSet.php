<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestSet;
use JmapClient\Requests\Mail\MailSubmissionParameters;

class MailSubmissionSet extends RequestSet {

    protected string $_space = 'urn:ietf:params:jmap:submission';
    protected string $_class = 'EmailSubmission';
    protected string $_parametersClass = MailSubmissionParameters::class;

    /**
     * Create a mail submission
     * 
     * @param string $id Submission identifier
     * @param MailSubmissionParameters|null $object Submission parameters object
     * 
     * @return MailSubmissionParameters The submission parameters for method chaining
     */
    public function create(string $id, $object = null): MailSubmissionParameters {
        return parent::create($id, $object);
    }

    /**
     * Update a mail submission
     * 
     * @param string $id Submission identifier
     * @param MailSubmissionParameters|null $object Submission parameters object
     * 
     * @return MailSubmissionParameters The submission parameters for method chaining
     */
    public function update(string $id, $object = null): MailSubmissionParameters {
        return parent::update($id, $object);
    }

    /**
     * Delete a mail submission
     * 
     * @param string $id Submission identifier
     * 
     * @return self
     */
    public function delete(string $id): self {
        return parent::delete($id);
    }

}
