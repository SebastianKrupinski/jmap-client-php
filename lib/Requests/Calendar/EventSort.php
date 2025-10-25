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

        // creates or updates parameter and assigns value
        $this->condition('created', $value);
        // return self for function chaining
        return $this;

    }

    public function updated(bool $value = false): self {

        // creates or updates parameter and assigns value
        $this->condition('updated', $value);
        // return self for function chaining
        return $this;

    }

    public function start(bool $value = false): self {

        // creates or updates parameter and assigns value
        $this->condition('start', $value);
        // return self for function chaining
        return $this;

    }

    public function uid(bool $value = false): self {

        // creates or updates parameter and assigns value
        $this->condition('uid', $value);
        // return self for function chaining
        return $this;

    }

    public function recurrence(bool $value = false): self {

        // creates or updates parameter and assigns value
        $this->condition('recurrenceId', $value);
        // return self for function chaining
        return $this;

    }

}
