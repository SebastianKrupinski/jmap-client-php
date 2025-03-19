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
namespace JmapClient\Authentication;

class Cookie implements IAuthenticationCookie {

    public function __construct (
        private ?string $CookieAuthenticationLocation = null,
        private ?string $CookieStoreId = null,
        private $CookieRetrieveStore = null,
        private $CookieDepositStore = null,
    ) {}

    public function getCookieAuthenticationLocation(): ?string {
        return $this->CookieAuthenticationLocation;
    }

    public function setCookieAuthenticationLocation(?string $value): void {
        $this->CookieAuthenticationLocation = $value;
    }

    public function getCookieStoreId(): mixed {
        return $this->CookieStoreId;
    }

    public function setCookieStoreId(mixed $value): void {
        $this->CookieStoreId = $value;
    }

    public function getCookieStoreRetrieve(): ?callable {
        return $this->CookieRetrieveStore;
    }

    public function setCookieStoreRetrieve(?callable $value): void {
        $this->CookieRetrieveStore = $value;
    }

    public function getCookieStoreDeposit(): ?callable {
        return $this->CookieDepositStore;
    }

    public function setCookieStoreDeposit(?callable $value): void {
        $this->CookieDepositStore = $value;
    }
    
}
