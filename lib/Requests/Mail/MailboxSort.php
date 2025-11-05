<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestSort;

class MailboxSort extends RequestSort
{
    public function name(bool $value = false): static
    {
        $this->condition('name', $value);
        return $this;
    }

    public function order(bool $value = false): static
    {
        $this->condition('sortOrder', $value);
        return $this;
    }

}
