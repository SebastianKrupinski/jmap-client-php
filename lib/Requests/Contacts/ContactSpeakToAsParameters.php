<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestParameters;

class ContactSpeakToAsParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'SpeakToAs');
    }

    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    /**
     * Set the grammatical gender for addressing this contact
     * Standard values: animate, common, feminine, inanimate, masculine, neuter
     *
     * @param string $value
     * @return static
     */
    public function grammaticalGender(string $value): static
    {
        $this->parameter('grammaticalGender', $value);
        return $this;
    }

    /**
     * Add a pronouns entry for this contact
     *
     * @param string $id
     * @return ContactPronounsParameters
     */
    public function pronouns(string $id): ContactPronounsParameters
    {
        if (!isset($this->_parameters->pronouns?->$id)) {
            $this->parameterStructured('pronouns', $id, new \stdClass());
        }
        return new ContactPronounsParameters($this->_parameters->pronouns->$id);
    }
}
