<?php

declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactResourceParameters extends ResponseParameters
{
    public function type(): string|null
    {
        return $this->parameter('@type');
    }

    public function uri(): string|null
    {
        return $this->parameter('uri');
    }

    public function kind(): string|null
    {
        return $this->parameter('kind');
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
