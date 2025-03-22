<?php
declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactResourceParameters extends ResponseParameters
{

    public function __construct(array $response = []) {

        parent::__construct($response);

    }

    public function type(): string|null {
        
        return $this->parameter('@type');

    }

    public function uri(): string|null {
        
        return $this->parameter('uri');

    }

    public function kind(): string|null {
        
        return $this->parameter('kind');

    }

    public function context(): array|null {
        
        $collection = $this->parameter('contexts') ?? [];
        return array_keys($collection);

    }

    public function priority(): int|null {
        
        return $this->parameter('pref');

    }

    public function label(): string|null {
        
        return $this->parameter('label');

    }
    
}
