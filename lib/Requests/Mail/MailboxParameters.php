<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestParameters;

class MailboxParameters extends RequestParameters
{
    private const PARAMETER_LIST = ['parentId', 'name', 'role', 'sortOrder', 'isSubscribed', 'shareWith'];

    public function parametersRaw(array $value): static
    {
        $this->_parameters = (object) array_intersect_key($value, array_flip(self::PARAMETER_LIST));
        return $this;
    }

    public function in(string|null $value): static
    {
        $this->parameter('parentId', $value);
        return $this;
    }

    public function label(string $value): static
    {
        $this->parameter('name', $value);
        return $this;
    }

    public function role(string|null $value): static
    {
        $this->parameter('role', $value);
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

    public function sharees(string $id, ?MailboxPermissions $permissions = null): MailboxPermissions
    {
        if (!isset($this->_parameters->shareWith) || !is_object($this->_parameters->shareWith)) {
            $this->_parameters->shareWith = new \stdClass();
        }
        if ($permissions !== null) {
            $permissions->bind($this->_parameters->shareWith->$id);
        } elseif (!isset($this->_parameters->shareWith->$id)) {
            $this->_parameters->shareWith->$id = new \stdClass();
        }
        return new MailboxPermissions($this->_parameters->shareWith->$id);
    }
}
