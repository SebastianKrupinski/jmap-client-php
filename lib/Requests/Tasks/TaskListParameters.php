<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Tasks;

use JmapClient\Requests\RequestParameters;

class TaskListParameters extends RequestParameters
{
    public function __construct(&$parameters = null) {

        parent::__construct($parameters);

    }

    public function label(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('name', $value);
        // return self for function chaining
        return $this;

    }

    public function description(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('description', $value);
        // return self for function chaining
        return $this;

    }

    public function color(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('color', $value);
        // return self for function chaining
        return $this;

    }

    public function priority(int $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('sortOrder', $value);
        // return self for function chaining
        return $this;

    }

    public function subscribed(bool $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('isSubscribed', $value);
        // return self for function chaining
        return $this;

    }

    public function visible(bool $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('isVisible', $value);
        // return self for function chaining
        return $this;

    }

    public function default(bool $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('isDefault', $value);
        // return self for function chaining
        return $this;

    }

    public function timezone(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('timeZone', $value);
        // return self for function chaining
        return $this;

    }

    public function sharees(string ...$value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('shareWith', $value);
        // return self for function chaining
        return $this;

    }

}
