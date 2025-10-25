<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestSet;

class AddressBookSet extends RequestSet {

    protected string $_space = 'urn:ietf:params:jmap:contacts';
    protected string $_class = 'AddressBook';
    protected string $_parametersClass = AddressBookParameters::class;

    /**
     * Create an address book
     * 
     * @param string $id Address book identifier
     * @param AddressBookParameters|null $object Address book parameters object
     * 
     * @return AddressBookParameters The address book parameters for method chaining
     */
    public function create(string $id, $object = null): AddressBookParameters {
        return parent::create($id, $object);
    }

    /**
     * Update an address book
     * 
     * @param string $id Address book identifier
     * @param AddressBookParameters|null $object Address book parameters object
     * 
     * @return AddressBookParameters The address book parameters for method chaining
     */
    public function update(string $id, $object = null): AddressBookParameters {
        return parent::update($id, $object);
    }

    /**
     * Delete an address book
     * 
     * @param string $id Address book identifier
     * 
     * @return self
     */
    public function delete(string $id): self {
        return parent::delete($id);
    }

}
