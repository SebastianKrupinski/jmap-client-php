<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Mail;

use JmapClient\Responses\ResponseParameters;

class MailSubmissionParameters extends ResponseParameters
{
    public function id(): string|null
    {
        return $this->parameter('id');
    }

    public function identity(): string|null
    {
        return $this->parameter('identityId');
    }

    public function message(): string|null
    {
        return $this->parameter('emailId');
    }

    public function thread(): string|null
    {
        return $this->parameter('threadId');
    }

}
