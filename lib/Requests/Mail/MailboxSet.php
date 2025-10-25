<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestSet;
use JmapClient\Requests\Mail\MailboxParameters;

class MailboxSet extends RequestSet {

    protected string $_space = 'urn:ietf:params:jmap:mail';
    protected string $_class = 'Mailbox';
    protected string $_parametersClass = MailboxParameters::class;

    /**
     * Create a mailbox
     * 
     * @param string $id Mailbox identifier
     * @param MailboxParameters|null $object Mailbox parameters object
     * 
     * @return MailboxParameters The mailbox parameters for method chaining
     */
    public function create(string $id, $object = null): MailboxParameters {
        return parent::create($id, $object);
    }

    /**
     * Update a mailbox
     * 
     * @param string $id Mailbox identifier
     * @param MailboxParameters|null $object Mailbox parameters object
     * 
     * @return MailboxParameters The mailbox parameters for method chaining
     */
    public function update(string $id, $object = null): MailboxParameters {
        return parent::update($id, $object);
    }

    /**
     * Delete a mailbox
     * 
     * @param string $id Mailbox identifier
     * 
     * @return self
     */
    public function delete(string $id): self {
        return parent::delete($id);
    }

    /**
     * Set whether to remove emails when deleting the mailbox
     * 
     * @param bool $value Whether to remove emails on destruction
     * 
     * @return self
     */
    public function destroyContents(bool $value): self {

        
        $this->_command['onDestroyRemoveEmails'] = $value;

        return $this;
        
    }

}
