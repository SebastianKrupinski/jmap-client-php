<?php
declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactOrganizationParameters extends ResponseParameters
{
    public function name(): string|null {
        return $this->parameter('name');
    }

    public function role(): string|null {
        return $this->parameter('role');
    }

    public function department(): string|null {
        return $this->parameter('department');
    }
}
