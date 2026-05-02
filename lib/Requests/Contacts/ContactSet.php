<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\Interfaces\RequestPatchInterface;
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
     * Patch a contact
     *
     * @param string $id Contact identifier
     * @param ContactParameters|RequestPatchInterface|null $object Optional structured or patch object
     * @return RequestPatchInterface The patch object for method chaining
     */
    public function patch(string $id, mixed $object = null): RequestPatchInterface
    {
        return parent::patch($id, $object);
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
