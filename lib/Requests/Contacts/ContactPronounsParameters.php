<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestParameters;

class ContactPronounsParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'Pronouns');
    }

    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    /**
     * Set the pronouns string
     * Examples: "she/her", "they/them", "xe/xir"
     *
     * @param string $value
     * @return static
     */
    public function pronouns(string $value): static
    {
        $this->parameter('pronouns', $value);
        return $this;
    }

    /**
     * Set the contexts for these pronouns
     * Standard values: private, work
     *
     * @param string ...$values
     * @return static
     */
    public function contexts(string ...$values): static
    {
        $collection = [];
        foreach ($values as $value) {
            $collection[$value] = true;
        }
        $this->parameter('contexts', (object)$collection);
        return $this;
    }

    /**
     * Set the preference for these pronouns (1-100, lower is more preferred)
     *
     * @param int $value
     * @return static
     */
    public function preference(int $value): static
    {
        $this->parameter('pref', $value);
        return $this;
    }
}
