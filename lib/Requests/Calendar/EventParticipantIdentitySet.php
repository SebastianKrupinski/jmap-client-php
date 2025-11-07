<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Calendar;

use JmapClient\Requests\RequestSet;

/**
 * @extends RequestSet<EventParticipantIdentityParameters>
 */
class EventParticipantIdentitySet extends RequestSet
{
    protected string $_space = 'urn:ietf:params:jmap:calendars';
    protected string $_class = 'ParticipantIdentity';
    protected string $_parametersClass = EventParticipantIdentityParameters::class;

    /**
     * Create a participant identity
     *
     * @param string $id Identity identifier
     * @param EventParticipantIdentityParameters|null $object Identity parameters object
     *
     * @return EventParticipantIdentityParameters The identity parameters for method chaining
     */
    public function create(string $id, mixed $object = null): EventParticipantIdentityParameters
    {
        return parent::create($id, $object);
    }

    /**
     * Update a participant identity
     *
     * @param string $id Identity identifier
     * @param EventParticipantIdentityParameters|null $object Identity parameters object
     *
     * @return EventParticipantIdentityParameters The identity parameters for method chaining
     */
    public function update(string $id, mixed $object = null): EventParticipantIdentityParameters
    {
        return parent::update($id, $object);
    }

    /**
     * Delete a participant identity
     *
     * @param string $id Identity identifier
     * @return self
     */
    public function delete(string $id): static
    {
        return parent::delete($id);
    }

    /**
     * Set the default participant identity after successful operations
     *
     * @param string $id Identity identifier to set as default (use "#" prefix for creation references)
     *
     * @return self
     */
    public function setDefaultOnSuccess(string $id): static
    {
        $this->_command['onSuccessSetIsDefault'] = $id;
        return $this;
    }
}
