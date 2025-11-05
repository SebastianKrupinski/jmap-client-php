<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestSort;

class ContactSort extends RequestSort
{
    public function created(bool $value = false): static
    {


        $this->condition('created', $value);

        return $this;

    }

    public function updated(bool $value = false): static
    {


        $this->condition('updated', $value);

        return $this;

    }

    public function nameGiven(bool $value = false): static
    {


        $this->condition('name/given', $value);

        return $this;

    }

    public function nameSurname(bool $value = false): static
    {


        $this->condition('name/surname', $value);

        return $this;

    }

    public function nameSurname2(bool $value = false): static
    {


        $this->condition('name/surname2', $value);

        return $this;

    }

}
