<?php
declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactTitleParameters extends ResponseParameters {

    public function type(): string|null {
        return $this->parameter('@type');
    }

    public function name(): string|null {
        return $this->parameter('name');
    }

    public function kind(): string|null {
        return $this->parameter('kind');
    }

    public function context(): array {
        return array_keys($this->parameter('contexts') ?? []);
    }

    public function relation(): string|null {
        return $this->parameter('organizationId');
    }

}
