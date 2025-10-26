<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestPermissions;

class AddressBookPermissions extends RequestPermissions {

    public function read(bool $value): self {
        $this->parameter('mayRead', $value);
        return $this;
    }

    public function write(bool $value): self {
        $this->parameter('mayWrite', $value);
        return $this;
    }

    public function delete(bool $value): self {
        $this->parameter('mayDelete', $value);
        return $this;
    }

    public function manage(bool $value): self {
        $this->parameter('mayAdmin', $value);
        return $this;
    }

}
