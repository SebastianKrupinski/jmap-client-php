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
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestSort;

class MailSort extends RequestSort {

    public function received(bool $value = false): self {
        $this->condition('receivedAt', $value);
        return $this;
    }

    public function sent(bool $value = false): self {
        $this->condition('sentAt', $value);
        return $this;
    }

    public function from(bool $value = false): self {
        $this->condition('from', $value);
        return $this;
    }

    public function to(bool $value = false): self {
        $this->condition('to', $value);
        return $this;
    }

    public function subject(bool $value = false): self {
        $this->condition('subject', $value);
        return $this;
    }

    public function size(bool $value = false): self {
        $this->condition('size', $value);
        return $this;
    }

    public function keyword(bool $value = false): self {
        $this->condition('hasKeyword', $value);
        return $this;
    }

}
