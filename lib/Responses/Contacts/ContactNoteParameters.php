<?php
declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactNoteParameters extends ResponseParameters
{
    public function content(): string|null {
        return $this->parameter('content');
    }
}
