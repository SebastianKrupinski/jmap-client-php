<?php

declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactOrganizationParameters extends ResponseParameters
{
    public function type(): string|null
    {
        return $this->parameter('@type');
    }

    public function context(): array
    {
        return array_keys($this->parameter('contexts') ?? []);
    }

    public function name(): string|null
    {
        return $this->parameter('name');
    }

    public function sorting(): string|null
    {
        return $this->parameter('sortAs');
    }

    public function units(): array|null
    {
        $collection = $this->parameter('units') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactOrganizationUnitParameters($data);
        }
        return $collection;
    }
}
