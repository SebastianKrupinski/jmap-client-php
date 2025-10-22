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
namespace JmapClient\Requests\Core;

use DateTimeInterface;
use JmapClient\Requests\RequestParameters;

class SubscriptionParameters extends RequestParameters
{

    public function id(string $value): self {
        $this->parameter('id', $value);
        return $this;
    }

    public function device(string $value): self {
        $this->parameter('deviceClientId', $value);
        return $this;
    }
    
    public function url(string $value): self {
        $this->parameter('url', $value);
        return $this;
    }
    
    public function expiration(DateTimeInterface $value): self {
        $this->parameter('expires', $value->format(self::DATE_FORMAT_UTC));
        return $this;
    }

    public function verification(string $value): self {
        $this->parameter('verificationCode', $value);
        return $this;
    }

    public function keys(string $value): self {
        $this->parameter('keys', $value);
        return $this;
    }

}
