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
use JmapClient\Responses\Mail\MailPart;

class MailParameters extends ResponseParameters
{

    protected MailPart $_structure;
    
    public function __construct(array $response = []) {

        if (isset($response['bodyStructure'])) {
            $this->_structure = new MailPart($response['bodyStructure']);
        }
        
        parent::__construct($response);

    }

    public function in(): array|null {
        
        // return value of parameter
        return array_keys($this->parameter('mailboxIds'));

    }

    public function id(): string|null {
        
        // return value of parameter
        return $this->parameter('id');

    }

    public function thread(): string|null {
        
        // return value of parameter
        return $this->parameter('threadId');

    }

    public function blob(): string|null {
        
        // return value of parameter
        return $this->parameter('blobId');

    }

    public function received(): string|null {
        
        // return value of parameter
        return $this->parameter('receivedAt');

    }

    public function sent(): string|null {
        
        // return value of parameter
        return $this->parameter('sentAt');

    }

    public function size(): int|null {
        
        // return value of parameter
        return $this->parameter('size');

    }

    public function from(): array|null {
        
        // return value of parameter
        return $this->parameter('from');

    }

    public function to(): array|null {
        
        // return value of parameter
        return $this->parameter('to');

    }

    public function cc(): array|null {
        
        // return value of parameter
        return $this->parameter('cc');

    }

    public function bcc(): array|null {
        
        // return value of parameter
        return $this->parameter('bcc');

    }

    public function subject(): string|null {
        
        // return value of parameter
        return $this->parameter('subject');

    }

    public function body(): string|null {
        
        // return value of parameter
        return !empty($this->parameter('htmlBody')) ? : $this->parameter('textBody');

    }

    public function bodyHtml(): string|null {
        
        // return value of parameter
        return $this->parameter('htmlBody');

    }

    public function bodyText(): string|null {
        
        // return value of parameter
        return $this->parameter('textBody');

    }

    public function structure(): MailPart|null {
        
        if (!$this->_structure) {
            $this->_structure = new MailPart($this->parameter('bodyStructure'));
        }
        // return value of parameter
        return $this->parameter('bodyStructure');

    }

    public function attachments(): array|null {
        
        // return value of parameter
        return $this->parameter('attachments');

    }

}
