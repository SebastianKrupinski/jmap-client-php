<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Tasks;

use DateTimeImmutable;
use JmapClient\Responses\ResponseDate;
use JmapClient\Responses\ResponseParameters;

class TaskParameters extends ResponseParameters
{
    /* Metadata Properties */

    public function in(): array|null
    {
        return $this->parameterKeys('taskListId');
    }

    public function id(): string|null
    {
        return $this->parameter('id');
    }

    public function uid(): string|null
    {
        return $this->parameter('uid');
    }

    public function created(): DateTimeImmutable|null
    {
        return ResponseDate::utc($this->parameter('created'));
    }

    public function updated(): DateTimeImmutable|null
    {
        return ResponseDate::utc($this->parameter('updated'));
    }
}
