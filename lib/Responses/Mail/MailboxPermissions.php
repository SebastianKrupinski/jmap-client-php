<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Mail;

use JmapClient\Responses\ResponsePermissions;

class MailboxPermissions extends ResponsePermissions
{
    public function readItems(): bool|null
    {
        return $this->parameter('mayReadItems');
    }

    public function addItems(): bool|null
    {
        return $this->parameter('mayAddItems');
    }

    public function removeItems(): bool|null
    {
        return $this->parameter('mayRemoveItems');
    }

    public function setSeen(): bool|null
    {
        return $this->parameter('maySetSeen');
    }

    public function setKeywords(): bool|null
    {
        return $this->parameter('maySetKeywords');
    }

    public function createChild(): bool|null
    {
        return $this->parameter('mayCreateChild');
    }

    public function rename(): bool|null
    {
        return $this->parameter('mayRename');
    }

    public function delete(): bool|null
    {
        return $this->parameter('mayDelete');
    }

    public function submit(): bool|null
    {
        return $this->parameter('maySubmit');
    }

}
