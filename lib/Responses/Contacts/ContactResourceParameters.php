<?php

declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use JmapClient\Enums\Contacts\ContactContext;
use JmapClient\Enums\Contacts\ContactResourceKind;
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

    public function kind(): ContactResourceKind|string|null
    {
        $value = $this->parameter('kind');
        return is_string($value) ? (ContactResourceKind::tryFrom($value) ?? $value) : null;
    }

    public function context(): array
    {
        return array_map(fn ($value) => ContactContext::tryFrom((string) $value) ?? (string) $value, array_keys($this->parameter('contexts') ?? []));
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
