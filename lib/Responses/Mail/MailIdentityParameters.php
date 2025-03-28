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
namespace JmapClient\Responses\Mail;

use JmapClient\Responses\ResponseParameters;

class MailIdentityParameters extends ResponseParameters {
    
    public function id(): string|null {
        return $this->parameter('id');
    }

    public function name(): string|null {
        return $this->parameter('name');
    }

    public function address(): string|null {
        return $this->parameter('email');
    }

    public function reply(): array|null {
        return $this->parameter('replyTo');
    }

    public function bcc(): array|null {
        return $this->parameter('bcc');
    }

    public function signature(): string|null {
        return !empty($this->parameter('htmlSignature')) ? : $this->parameter('textSignature');
    }

    public function signatureHtml(): string|null {
        return $this->parameter('htmlSignature');
    }

    public function signatureText(): string|null {
        return $this->parameter('textSignature');
    }

    public function deletable(): bool {
        return $this->parameter('mayDelete');
    }

}
