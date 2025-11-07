<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestParameters;

class AddressBookParameters extends RequestParameters
{
    public function label(string $value): static
    {
        $this->parameter('name', $value);
        return $this;
    }

    public function description(string $value): static
    {
        $this->parameter('description', $value);
        return $this;
    }

    public function priority(int $value): static
    {
        $this->parameter('sortOrder', $value);
        return $this;
    }

    public function subscribed(bool $value): static
    {
        $this->parameter('isSubscribed', $value);
        return $this;
    }

    public function default(bool $value): static
    {
        $this->parameter('isDefault', $value);
        return $this;
    }

    public function sharees(string $id, ?AddressBookPermissions $permissions = null): AddressBookPermissions
    {
        if (!isset($this->_parameters->shareWith?->$id)) {
            $this->parameterStructured('shareWith', $id, $permissions ?? new \stdClass());
        } else {
            $permissions->bind($this->_parameters->shareWith->$id);
        }
        return new AddressBookPermissions($this->_parameters->shareWith->$id);
    }
}
