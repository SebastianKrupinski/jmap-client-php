<?php
declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactEmailParameters extends ResponseParameters
{
    public function email(): string|null {
        return $this->parameter('email');
    }

    public function type(): string|null {
        return $this->parameter('type');
    }
}
