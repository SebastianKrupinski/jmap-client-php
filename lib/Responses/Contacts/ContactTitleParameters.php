<?php
declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactTitleParameters extends ResponseParameters
{

    public function __construct(array $response = []) {

        parent::__construct($response);

    }

    public function type(): string|null {
        
        return $this->parameter('@type');

    }

    public function name(): string|null {
        
        return $this->parameter('name');

    }

    public function king (): string|null {
        
        return $this->parameter('kind');

    }

    public function context(): array|null {
        
        $collection = $this->parameter('contexts') ?? [];
        return array_keys($collection);

    }

    public function relation(): string|null {

        return $this->parameter('organizationId');

    }

}
