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
namespace JmapClient\Requests\Contacts;

use DateTimeInterface;
use JmapClient\Requests\RequestParameters;

class ContactNoteParameters extends RequestParameters {

    public function __construct(&$parameters = null) {
        parent::__construct($parameters);
        $this->parameter('@type', 'Note');
    }

    public function type(string $value): static {
        $this->parameter('@type', $value);
        return $this;
    }

    public function contents(string $value): static {
        $this->parameter('note', $value);
        return $this;
    }

    public function created(DateTimeInterface $value): static {
        $this->parameter('created', $value->format(static::DATE_FORMAT_UTC));
        return $this;
    }

    public function author(): ContactNoteAuthorParameters {
        if (!isset($this->_parameters->author)) {
            $this->parameter('author', new \stdClass());
        }
        return new ContactNoteAuthorParameters($this->_parameters->author);
    }

}
