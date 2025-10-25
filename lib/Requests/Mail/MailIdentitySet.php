<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestSet;

class MailIdentitySet extends RequestSet {

    protected string $_space = 'urn:ietf:params:jmap:submission';
    protected string $_class = 'Identity';
    protected string $_parametersClass = MailIdentityParameters::class;

    /**
     * Create a mail identity
     * 
     * @param string $id Identity identifier
     * @param MailIdentityParameters|null $object Identity parameters object
     * 
     * @return MailIdentityParameters The identity parameters for method chaining
     */
    public function create(string $id, $object = null): MailIdentityParameters {
        return parent::create($id, $object);
    }

    /**
     * Update a mail identity
     * 
     * @param string $id Identity identifier
     * @param MailIdentityParameters|null $object Identity parameters object
     * 
     * @return MailIdentityParameters The identity parameters for method chaining
     */
    public function update(string $id, $object = null): MailIdentityParameters {
        return parent::update($id, $object);
    }

    /**
     * Delete a mail identity
     * 
     * @param string $id Identity identifier
     * 
     * @return self
     */
    public function delete(string $id): self {
        return parent::delete($id);
    }

}
