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

class Bearer implements IAuthenticationBearer
{
    public function __construct (
        private ?string $Id = null,
        private ?string $Token = null,
        private int $Expiry = 0,
        private ?string $Location = null
    ) {}

    public function getId(): ?string {
        return $this->Id;
    }

    public function setId(?string $value): void {
        $this->Id = $value;
    }

    public function getToken(): ?string {
        return $this->Token;
    }

    public function setToken(?string $value): void {
        $this->Token = $value;
    }

    public function getExpiry(): ?int {
        return $this->Expiry;
    }

    public function setExpiry(?int $value): void {
        $this->Expiry = $value;
    }
    
    public function getLocation(): ?string {
        return $this->Location;
    }

    public function setLocation(?string $value): void {
        $this->Location = $value;
    }

}
