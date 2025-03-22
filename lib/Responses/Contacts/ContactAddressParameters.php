<?php
declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use DateTimeZone;
use JmapClient\Responses\ResponseParameters;

class ContactAddressParameters extends ResponseParameters {

    public function type(): string|null {

        return $this->parameter('@type');

    }
    
    public function full(): string|null {

        return $this->parameter('full');

    }

    public function components(): array {
        
        $components = $this->parameter('components') ?? [];
        foreach ($components as $key => $data) {
            $components[$key] = new ContactComponentParameters($data);
        }
        return $components;
    }

    public function separator(): string|null {

        return $this->parameter('defaultSeparator');

    }

    public function ordered(): bool|null {

        return (bool)$this->parameter('isOrdered');

    }

    public function country(): string|null {

        return $this->parameter('country');

    }

    public function coordinates(): string|null {

        return $this->parameter('coordinates');

    }

    public function timeZone(): DateTimeZone|null {

        $tz = $this->parameter('timeZone');
        return ($tz) ? new DateTimeZone($tz) : null;

    }

    public function phoneticScript(): string|null {

        return $this->parameter('phoneticScript');

    }

    public function phoneticSystem(): string|null {

        return $this->parameter('phoneticSystem');

    }
    
}
