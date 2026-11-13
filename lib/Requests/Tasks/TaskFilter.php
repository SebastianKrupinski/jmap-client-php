<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Tasks;

use DateTimeInterface;
use JmapClient\Requests\RequestDate;
use JmapClient\Requests\RequestFilter;

class TaskFilter extends RequestFilter
{
    public function in(string ...$value): static
    {
        $this->condition('inTaskList', $value);

        return $this;
    }

    public function uid(string $value): static
    {
        $this->condition('uid', $value);

        return $this;
    }

    public function after(DateTimeInterface $value): static
    {
        $this->condition('after', RequestDate::local($value));

        return $this;
    }

    public function before(DateTimeInterface $value): static
    {
        $this->condition('before', RequestDate::local($value));

        return $this;
    }
}
