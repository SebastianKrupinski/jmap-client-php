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
        
        
        $this->parameter('name', $value);
        
        return $this;

    }

    public function description(string $value): self {
        
        
        $this->parameter('description', $value);
        
        return $this;

    }

    public function color(string $value): self {
        
        
        $this->parameter('color', $value);
        
        return $this;

    }

    public function priority(int $value): self {
        
        
        $this->parameter('sortOrder', $value);
        
        return $this;

    }

    public function subscribed(bool $value): self {
        
        
        $this->parameter('isSubscribed', $value);
        
        return $this;

    }

    public function visible(bool $value): self {
        
        
        $this->parameter('isVisible', $value);
        
        return $this;

    }

    public function default(bool $value): self {
        
        
        $this->parameter('isDefault', $value);
        
        return $this;

    }

    public function timezone(string $value): self {
        
        
        $this->parameter('timeZone', $value);
        
        return $this;

    }

    public function sharees(string ...$value): self {
        
        
        $this->parameter('shareWith', $value);
        
        return $this;

    }

}
