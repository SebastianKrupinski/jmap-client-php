<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Calendar;

use JmapClient\Requests\RequestParameters;

class CalendarParameters extends RequestParameters
{
    public function label(string $value): static
    {
        $this->parameter('name', $value);
        return $this;
    }

    public function description(string $value): static
    {
        $this->parameter('description', $value);
        return $this;
    }

    public function color(string $value): static
    {
        $this->parameter('color', $value);
        return $this;
    }

    public function priority(int $value): static
    {
        $this->parameter('sortOrder', $value);
        return $this;
    }

    public function subscribed(bool $value): static
    {
        $this->parameter('isSubscribed', $value);
        return $this;
    }

    public function visible(bool $value): static
    {
        $this->parameter('isVisible', $value);
        return $this;
    }

    public function default(bool $value): static
    {
        $this->parameter('isDefault', $value);
        return $this;
    }

    public function timezone(string $value): static
    {
        $this->parameter('timeZone', $value);
        return $this;
    }

    public function sharees(string $id, ?CalendarPermissions $permissions = null): CalendarPermissions
    {
        if (!isset($this->_parameters->shareWith?->$id)) {
            $this->parameterStructured('shareWith', $id, $permissions ?? new \stdClass());
        } else {
            $permissions->bind($this->_parameters->shareWith->$id);
        }
        return new CalendarPermissions($this->_parameters->shareWith->$id);
    }

}
