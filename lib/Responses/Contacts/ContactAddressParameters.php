<?php

declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use DateTimeZone;
use JmapClient\Enums\Contacts\ContactContext;
use JmapClient\Enums\Contacts\ContactPhoneticSystem;
use JmapClient\Responses\ResponseParameters;

class ContactAddressParameters extends ResponseParameters
{
    public function type(): string|null
    {
        return $this->parameter('@type');
    }

    public function full(): string|null
    {
        return $this->parameter('full');
    }

    public function components(): array
    {
        $components = $this->parameter('components') ?? [];
        foreach ($components as $key => $data) {
            $components[$key] = new ContactAddressComponentParameters($data);
        }
        return $components;
    }

    public function context(): array
    {
        return array_map(fn ($value) => ContactContext::tryFrom((string) $value) ?? (string) $value, array_keys((array) ($this->parameter('contexts') ?? [])));
    }

    public function separator(): string|null
    {
        return $this->parameter('defaultSeparator');
    }

    public function ordered(): bool|null
    {
        return (bool)$this->parameter('isOrdered');
    }

    public function country(): string|null
    {
        return $this->parameter('countryCode');
    }

    public function coordinates(): string|null
    {
        return $this->parameter('coordinates');
    }

    public function timeZone(): DateTimeZone|null
    {
        $tz = $this->parameter('timeZone');
        return ($tz) ? new DateTimeZone($tz) : null;
    }

    public function phoneticScript(): string|null
    {
        return $this->parameter('phoneticScript');
    }

    public function phoneticSystem(): ContactPhoneticSystem|string|null
    {
        $value = $this->parameter('phoneticSystem');
        return is_string($value) ? (ContactPhoneticSystem::tryFrom($value) ?? $value) : null;
    }
}
