<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestSet;
use JmapClient\Requests\Mail\MailParameters;

class MailSet extends RequestSet {

    protected string $_space = 'urn:ietf:params:jmap:mail';
    protected string $_class = 'Email';
    protected string $_parametersClass = MailParameters::class;

    /**
     * Create an email
     * 
     * @param string $id Email identifier
     * @param MailParameters|null $object Email parameters object
     * 
     * @return MailParameters The email parameters for method chaining
     */
    public function create(string $id, $object = null): MailParameters {
        return parent::create($id, $object);
    }

    /**
     * Update an email
     * 
     * @param string $id Email identifier
     * @param MailParameters|null $object Email parameters object
     * 
     * @return MailParameters The email parameters for method chaining
     */
    public function update(string $id, $object = null): MailParameters {
        return parent::update($id, $object);
    }

    /**
     * Delete an email
     * 
     * @param string $id Email identifier
     * 
     * @return self
     */
    public function delete(string $id): self {
        return parent::delete($id);
    }

}
