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

class TaskFilter extends RequestFilter {
    
    public function in(string ...$value): self {

        // creates or updates parameter and assigns value
        $this->condition('inTaskList', $value);
        // return self for function chaining
        return $this;

    }

    public function uid(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('uid', $value);
        // return self for function chaining
        return $this;

    }
    
    public function after(DateTime|DateTimeImmutable $value): self {

        // creates or updates parameter and assigns value
        $this->condition('after', $value->format(self::DATE_FORMAT_LOCAL));
        // return self for function chaining
        return $this;

    }

    public function before(DateTime|DateTimeImmutable $value): self {

        // creates or updates parameter and assigns value
        $this->condition('before', $value->format(self::DATE_FORMAT_LOCAL));
        // return self for function chaining
        return $this;

    }

}
