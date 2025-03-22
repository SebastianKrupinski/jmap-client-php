<?php
declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactPhoneParameters extends ResponseParameters
{
    public function number(): string|null {
        return $this->parameter('number');
    }

    public function type(): string|null {
        return $this->parameter('type');
    }
}
