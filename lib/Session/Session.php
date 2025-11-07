<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Session;

/**
 * Session - Represents JMAP session data returned from authentication
 */
final class Session
{
    protected CapabilityCollection $_capabilities;
    protected AccountCollection $_accounts;
    protected array $_primaryAccounts;
    protected string $_username;
    protected string $_commandUrl;
    protected string $_downloadUrl;
    protected string $_uploadUrl;
    protected string $_eventUrl;
    protected string $_state;
    protected array $_rawData;

    /**
     * Constructor
     *
     * @param array $sessionData Raw session data from JMAP authentication
     */
    public function __construct(array $sessionData)
    {
        // Parse capabilities
        $this->_capabilities = new CapabilityCollection($sessionData['capabilities'] ?? []);
        // Parse accounts
        $this->_accounts = new AccountCollection($sessionData['accounts'] ?? []);
        // Parse primary accounts mapping
        $this->_primaryAccounts = $sessionData['primaryAccounts'] ?? [];
        // Parse simple string properties
        $this->_username = $sessionData['username'] ?? '';
        $this->_commandUrl = $sessionData['apiUrl'] ?? '';
        $this->_downloadUrl = $sessionData['downloadUrl'] ?? '';
        $this->_uploadUrl = $sessionData['uploadUrl'] ?? '';
        $this->_eventUrl = $sessionData['eventSourceUrl'] ?? '';
        $this->_state = $sessionData['state'] ?? '';
    }

    /**
     * Get server capabilities
     */
    public function capabilities(): CapabilityCollection
    {
        return $this->_capabilities;
    }

    /**
     * Check if server supports a capability
     */
    public function capable(string $capabilityId): bool
    {
        return $this->_capabilities->capable($capabilityId);
    }

    /**
     * Get a specific capability
     */
    public function capability(string $capabilityId): ?Capability
    {
        return $this->_capabilities->capability($capabilityId);
    }

    /**
     * Get all accounts
     */
    public function accounts(): AccountCollection
    {
        return $this->_accounts;
    }

    /**
     * Get a specific account by ID
     */
    public function account(string $accountId): ?Account
    {
        return $this->_accounts->account($accountId);
    }

    /**
     * Get the primary account object for a capability
     */
    public function primaryAccount(string $capabilityId): ?Account
    {
        $accountId = $this->_primaryAccounts[$capabilityId] ?? null;
        return $accountId ? $this->account($accountId) : null;
    }

    /**
     * Get authenticated username
     */
    public function username(): string
    {
        return $this->_username;
    }

    /**
     * Get API URL
     */
    public function commandUrl(): string
    {
        return $this->_commandUrl;
    }

    /**
     * Get download URL template
     */
    public function downloadUrl(): string
    {
        return $this->_downloadUrl;
    }

    /**
     * Get upload URL template
     */
    public function uploadUrl(): string
    {
        return $this->_uploadUrl;
    }

    /**
     * Get event URL template
     */
    public function eventUrl(): string
    {
        return $this->_eventUrl;
    }

    /**
     * Get session state
     */
    public function state(): string
    {
        return $this->_state;
    }

    /**
     * Get raw session data
     */
    public function getRawData(): array
    {
        return $this->_rawData;
    }

    /**
     * Check if session has a property
     */
    public function hasProperty(string $property): bool
    {
        return isset($this->_rawData[$property]);
    }

    /**
     * Get a raw property value
     */
    public function getProperty(string $property, mixed $default = null): mixed
    {
        return $this->_rawData[$property] ?? $default;
    }
}
