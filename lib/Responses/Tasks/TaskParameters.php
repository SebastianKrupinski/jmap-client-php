<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses\Tasks;

use DateTimeImmutable;
use JmapClient\Responses\ResponseParameters;

class TaskParameters extends ResponseParameters {

    /* Metadata Properties */

    public function in(): array|null {
        return array_keys($this->parameter('taskListId'));
    }
    
    public function id(): string|null {
        return $this->parameter('id');
    }

    public function uid(): string|null {
        return $this->parameter('uid');
    }

    public function created(): DateTimeImmutable|null {
        $value = $this->parameter('created');
        return ($value) ? new DateTimeImmutable($value) : null;
    }

    public function updated(): DateTimeImmutable|null {
        $value = $this->parameter('updated');
        return ($value) ? new DateTimeImmutable($value) : null;
    }

}
