<?php
declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactNoteAuthorParameters extends ResponseParameters {

    public function type(): string|null {
        return $this->parameter('@type');
    }

    public function name(): string|null {
        return $this->parameter('name');
    }
}
