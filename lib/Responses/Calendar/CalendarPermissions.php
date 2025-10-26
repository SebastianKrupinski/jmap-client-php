<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses\Calendar;

use JmapClient\Responses\ResponsePermissions;

class CalendarPermissions extends ResponsePermissions {

    public function availability(): bool|null {
        return $this->parameter('mayReadFreeBusy');
    }

    public function read(): bool|null {
        return $this->parameter('mayReadItems');
    }

    public function write(): bool|null {
        return $this->parameter('mayWriteAll');
    }

    public function writeOwn(): bool|null {
        return $this->parameter('mayWriteOwn');
    }

    public function writePrivate(): bool|null {
        return $this->parameter('mayWritePrivate');
    }

    public function rsvp(): bool|null {
        return $this->parameter('mayRSVP');
    }

    public function manage(): bool|null {
        return $this->parameter('mayAdmin');
    }

    public function delete(): bool|null {
        return $this->parameter('mayDelete');
    }

}
