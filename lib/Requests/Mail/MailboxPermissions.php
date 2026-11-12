<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestPermissions;

class MailboxPermissions extends RequestPermissions
{
    public function readItems(bool $value): static
    {
        $this->parameter('mayReadItems', $value);
        return $this;
    }

    public function addItems(bool $value): static
    {
        $this->parameter('mayAddItems', $value);
        return $this;
    }

    public function removeItems(bool $value): static
    {
        $this->parameter('mayRemoveItems', $value);
        return $this;
    }

    public function setSeen(bool $value): static
    {
        $this->parameter('maySetSeen', $value);
        return $this;
    }

    public function setKeywords(bool $value): static
    {
        $this->parameter('maySetKeywords', $value);
        return $this;
    }

    public function createChild(bool $value): static
    {
        $this->parameter('mayCreateChild', $value);
        return $this;
    }

    public function rename(bool $value): static
    {
        $this->parameter('mayRename', $value);
        return $this;
    }

    public function delete(bool $value): static
    {
        $this->parameter('mayDelete', $value);
        return $this;
    }

    public function submit(bool $value): static
    {
        $this->parameter('maySubmit', $value);
        return $this;
    }

    public function share(bool $value): static
    {
        $this->parameter('mayShare', $value);
        return $this;
    }
}
