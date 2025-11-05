<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Session;

/**
 * Account - Represents a single JMAP account
 */
final class Account
{
    protected string $_id;
    protected string $_name;
    protected bool $_isPersonal;
    protected bool $_isReadOnly;
    protected CapabilityCollection $_accountCapabilities;
    protected array $_data;

    /**
     * Constructor
     *
     * @param string $id Account ID
     * @param array $data Account data from session
     */
    public function __construct(string $id, array $data)
    {
        $this->_id = $id;
        $this->_data = $data;
        $this->_name = $data['name'] ?? '';
        $this->_isPersonal = $data['isPersonal'] ?? false;
        $this->_isReadOnly = $data['isReadOnly'] ?? false;

        // Parse account capabilities
        $capabilities = $data['accountCapabilities'] ?? [];
        $this->_accountCapabilities = new CapabilityCollection($capabilities);
    }

    /**
     * Get account ID
     */
    public function id(): string
    {
        return $this->_id;
    }

    /**
     * Get account name
     */
    public function name(): string
    {
        return $this->_name;
    }

    /**
     * Check if this is a personal account
     */
    public function personal(): bool
    {
        return $this->_isPersonal;
    }

    /**
     * Check if this account is read-only
     */
    public function immutable(): bool
    {
        return $this->_isReadOnly;
    }

    /**
     * Get account capabilities
     */
    public function capabilities(): CapabilityCollection
    {
        return $this->_accountCapabilities;
    }

    /**
     * Get capability data from this account
     */
    public function capability(string $capabilityId): ?Capability
    {
        return $this->_accountCapabilities->capability($capabilityId);
    }

    /**
     * Check if account has a specific capability
     */
    public function capable(string $capabilityId): bool
    {
        return $this->_accountCapabilities->capable($capabilityId);
    }

    /**
     * Get raw data value by property path
     */
    public function property(string $property, mixed $default = null): mixed
    {
        return $this->_data[$property] ?? $default;
    }

}
