<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
 * 
 * @author Sebastian Krupinski <krupinski01@gmail.com>
 *
 * @license AGPL-3.0-or-later
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
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
