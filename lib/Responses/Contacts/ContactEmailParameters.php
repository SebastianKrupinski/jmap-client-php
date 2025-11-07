<?php

declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactEmailParameters extends ResponseParameters
{
    public function type(): string|null
    {
        return $this->parameter('@type');
    }

    public function address(): string|null
    {
        return $this->parameter('address');
    }

    public function context(): array
    {
        return array_keys($this->parameter('contexts') ?? []);
    }

    public function priority(): int|null
    {
        return $this->parameter('pref');
    }

    public function label(): string|null
    {
        return $this->parameter('label');
    }
}
