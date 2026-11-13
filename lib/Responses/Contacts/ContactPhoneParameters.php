<?php

declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use JmapClient\Enums\Contacts\ContactContext;
use JmapClient\Enums\Contacts\ContactPhoneFeature;
use JmapClient\Responses\ResponseParameters;

class ContactPhoneParameters extends ResponseParameters
{
    public function type(): string|null
    {
        return $this->parameter('@type');
    }

    public function number(): string|null
    {
        return $this->parameter('number');
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

    public function features(): array
    {
        return array_map(fn ($value) => ContactPhoneFeature::tryFrom((string) $value) ?? (string) $value, array_keys($this->parameter('features') ?? []));
    }
}
