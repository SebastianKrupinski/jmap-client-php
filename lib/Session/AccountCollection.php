<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Session;

use JmapClient\ArrayObjectCollection;

/**
 * AccountCollection - Collection of JMAP accounts
 */
final class AccountCollection extends ArrayObjectCollection {

    /**
     * Constructor
     * 
     * @param array $accounts Array of account data keyed by account ID
     */
    public function __construct(array $accounts = []) {
        $items = [];
        
        foreach ($accounts as $id => $data) {
            $items[$id] = new Account($id, $data);
        }
        
        parent::__construct($items, Account::class);
    }

    /**
     * Get an account by ID
     */
    public function account(string $id): ?Account {
        return $this->findByKey($id);
    }

    /**
     * Get the first personal account (usually the primary account)
     */
    public function accountPrimary(): ?Account {
        $personal = $this->accountsPersonal();
        return $personal->first();
    }

    /**
     * Get all personal accounts
     */
    public function accountsPersonal(): AccountCollection {
        return $this->filter(static fn($account) => $account->personal());
    }

    /**
     * Get all read-only accounts
     */
    public function accountsImmutable(): AccountCollection {
        return $this->filter(static fn($account) => $account->immutable());
    }

    /**
     * Get accounts that support a specific capability
     */
    public function accountsByCapability(string $capabilityId): AccountCollection {
        return $this->filter(static fn($account) => $account->capable($capabilityId));
    }

}
