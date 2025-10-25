<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Calendar;

use JmapClient\Requests\RequestSort;

class EventSort extends RequestSort {
    
    public function created(bool $value = false): self {

        
        $this->condition('created', $value);
        
        return $this;

    }

    public function updated(bool $value = false): self {

        
        $this->condition('updated', $value);
        
        return $this;

    }

    public function start(bool $value = false): self {

        
        $this->condition('start', $value);
        
        return $this;

    }

    public function uid(bool $value = false): self {

        
        $this->condition('uid', $value);
        
        return $this;

    }

    public function recurrence(bool $value = false): self {

        
        $this->condition('recurrenceId', $value);
        
        return $this;

    }

}
