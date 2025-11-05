<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestSet;

/**
 * @extends RequestSet<ContactParameters>
 */
class ContactSet extends RequestSet
{
    protected string $_space = 'urn:ietf:params:jmap:contacts';
    protected string $_class = 'ContactCard';
    protected string $_parametersClass = ContactParameters::class;

    /**
     * Create a contact
     *
     * @param string $id Contact identifier
     * @param ContactParameters|null $object Contact parameters object
     *
     * @return ContactParameters The contact parameters for method chaining
     */
    public function create(string $id, mixed $object = null): ContactParameters
    {
        return parent::create($id, $object);
    }

    /**
     * Update a contact
     *
     * @param string $id Contact identifier
     * @param ContactParameters|null $object Contact parameters object
     *
     * @return ContactParameters The contact parameters for method chaining
     */
    public function update(string $id, mixed $object = null): ContactParameters
    {
        return parent::update($id, $object);
    }

    /**
     * Delete a contact
     *
     * @param string $id Contact identifier
     *
     * @return self
     */
    public function delete(string $id): static
    {
        return parent::delete($id);
    }

}
