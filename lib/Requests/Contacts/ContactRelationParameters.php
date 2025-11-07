<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestParameters;

class ContactRelationParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'Relation');
    }

    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    /**
     * Set the relationship types for this related contact
     * Standard values: acquaintance, agent, child, co-resident, co-worker, colleague, contact, crush,
     * date, emergency, friend, kin, me, met, muse, neighbor, parent, sibling, spouse, sweetheart
     *
     * @param string ...$values
     * @return static
     */
    public function relations(string ...$values): static
    {
        $collection = [];
        foreach ($values as $value) {
            $collection[$value] = true;
        }
        $this->parameter('relation', (object)$collection);
        return $this;
    }
}
