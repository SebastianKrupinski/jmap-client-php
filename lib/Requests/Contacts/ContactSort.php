<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestSort;

class ContactSort extends RequestSort {

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

    public function nameGiven(bool $value = false): self {

        // creates or updates parameter and assigns value
        $this->condition('name/given', $value);
        // return self for function chaining
        return $this;

    }

    public function nameSurname(bool $value = false): self {

        // creates or updates parameter and assigns value
        $this->condition('name/surname', $value);
        // return self for function chaining
        return $this;

    }

    public function nameSurname2(bool $value = false): self {

        // creates or updates parameter and assigns value
        $this->condition('name/surname2', $value);
        // return self for function chaining
        return $this;

    }

}
