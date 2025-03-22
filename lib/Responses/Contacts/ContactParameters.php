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
namespace JmapClient\Responses\Contacts;

use DateTimeImmutable;
use JmapClient\Responses\ResponseParameters;

class ContactParameters extends ResponseParameters
{
    
    public function __construct(array $response = []) {

        parent::__construct($response);

    }

    /* Metadata Properties */

    public function in(): array|null {
        
        $value = $this->parameter('addressbookIds');
        if ($value !== null) {
            return array_keys($value);
        }

        return null;

    }
    
    public function id(): string|null {

        return $this->parameter('id');

    }

    public function uid(): string|null {
        
        return $this->parameter('uid');

    }

    public function type(): string|null {
        
        return $this->parameter('@type');

    }

    public function version(): string|null {
        
        return $this->parameter('version');

    }

    public function created(): DateTimeImmutable|null {
        
        $value = $this->parameter('created');
        return ($value) ? new DateTimeImmutable($value) : null;

    }

    public function updated(): DateTimeImmutable|null {
        
        $value = $this->parameter('updated');
        return ($value) ? new DateTimeImmutable($value) : null;

    }

    public function source(): string|null {
        
        return $this->parameter('prodId');

    }

    public function kind(): string|null {

        return $this->parameter('kind') ?? 'individual';

    }

    public function language(): string|null {
        
        return $this->parameter('language');

    }

    public function members(): array|null {
        
        return $this->parameter('members');

    }

    public function relation(): string|null {
        
        return $this->parameter('relatedTo');

    }

    /** Name Properties */

    public function name(): ContactNameParameters|null {
        
        $value = $this->parameter('name');
        if ($value !== null) {
            return new ContactNameParameters($value);
        }
        return null;

    }

    public function aliases(): array|null {
        
        $collection = $this->parameter('nicknames') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactAliasParameters($data);
        }

        return $collection;

    }

    public function speakToAs(): string|null {
        
        return $this->parameter('speakToAs');

    }

    /** Personal Properties */
    
    public function anniversaries(): array|null {
        
        $collection = $this->parameter('anniversaries') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactAnniversaryParameters($data);
        }

        return $collection;

    }

    /** Organization Properties */

    public function titles(): array|null {
        $collection = $this->parameter('titles') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactTitleParameters($data);
        }
        return $collection;
    }

    public function organizations(): array|null {
        $collection = $this->parameter('organizations') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactOrganizationParameters($data);
        }
        return $collection;
    }

    /** Communication Properties */

    public function emails(): array|null {
        $collection = $this->parameter('emails') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactEmailParameters($data);
        }
        return $collection;
    }

    public function phones(): array|null {
        $collection = $this->parameter('phones') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactPhoneParameters($data);
        }
        return $collection;
    }

    public function addresses(): array|null {
        $collection = $this->parameter('addresses') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactAddressParameters($data);
        }
        return $collection;
    }

    /** Commentary Properties */

    public function tags(): array|null {
        $value = $this->parameter('keywords');
        return $value ? array_keys((array)$value) : null;
    }

    public function notes(): array|null {
        $collection = $this->parameter('notes') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactNoteParameters($data);
        }
        return $collection;
    }

    /** Resources Properties */

    public function crypto(): array|null {
        $collection = $this->parameter('cryptoKeys') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactCryptoParameters($data);
        }
        return $collection;
    }

    public function directories(): array|null {
        $collection = $this->parameter('directories') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactDirectoryParameters($data);
        }
        return $collection;
    }

    public function links(): array|null {
        $collection = $this->parameter('links') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactLinkParameters($data);
        }
        return $collection;
    }

    public function media(): array|null {
        $collection = $this->parameter('media') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactMediaParameters($data);
        }
        return $collection;
    }

}
