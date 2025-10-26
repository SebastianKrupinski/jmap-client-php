<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses\Mail;

use JmapClient\Responses\ResponseParameters;
use JmapClient\Responses\ResponsePermissions;

class MailboxParameters extends ResponseParameters {

    public function in(): string|null {
        return $this->parameter('parentId');
    }
    public function id(): string|null {
        return $this->parameter('id');
    }
    public function name(): string|null {
        return $this->parameter('name');
    }
    public function role(): string|null {
        return $this->parameter('role');
    }
    public function priority(): int|null {
        return $this->parameter('sortOrder');
    }
    public function rights(): MailboxPermissions|null {
        $rights = $this->parameter('myRights');
        if ($rights === null) {
            return null;
        }
        return new MailboxPermissions((array) $rights);
    }
    public function subscribed(): bool|null {
        return $this->parameter('isSubscribed');
    }
    public function objectsTotal(): int|null {
        return $this->parameter('totalEmails');
    }
    public function objectsUnseen(): int|null {
        return $this->parameter('unreadEmails');
    }
    public function threadsTotal(): int|null {
        return $this->parameter('totalThreads');
    }
    public function threadsUnseen(): int|null {
        return $this->parameter('unreadThreads');
    }

}
