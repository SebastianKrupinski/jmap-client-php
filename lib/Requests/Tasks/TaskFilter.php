<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Tasks;

use DateTime;
use DateTimeImmutable;
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

    public function after(DateTime|DateTimeImmutable $value): static
    {
        $this->condition('after', $value->format(self::DATE_FORMAT_LOCAL));

        return $this;
    }

    public function before(DateTime|DateTimeImmutable $value): static
    {
        $this->condition('before', $value->format(self::DATE_FORMAT_LOCAL));

        return $this;
    }
}
