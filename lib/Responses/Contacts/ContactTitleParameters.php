<?php

declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use JmapClient\Enums\Contacts\ContactContext;
use JmapClient\Enums\Contacts\ContactTitleKind;
use JmapClient\Responses\ResponseParameters;

class ContactTitleParameters extends ResponseParameters
{
    public function type(): string|null
    {
        return $this->parameter('@type');
    }

    public function name(): string|null
    {
        return $this->parameter('name');
    }

    public function kind(): ContactTitleKind|string|null
    {
        $value = $this->parameter('kind');
        return is_string($value) ? (ContactTitleKind::tryFrom($value) ?? $value) : null;
    }

    public function context(): array
    {
        return array_map(fn ($value) => ContactContext::tryFrom((string) $value) ?? (string) $value, array_keys($this->parameter('contexts') ?? []));
    }

    public function relation(): string|null
    {
        return $this->parameter('organizationId');
    }
}
