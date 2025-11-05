<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestParameters;

class MailSubmissionParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
    }

    public function identity(string $value): static
    {
        $this->parameter('identityId', $value);
        return $this;
    }

    public function message(string $value): static
    {
        $this->parameter('emailId', $value);
        return $this;
    }

    public function from(string $value): static
    {
        $this->parameterStructured('envelope', 'mailFrom', (object) ['email' => $value]);
        return $this;
    }

    public function to(array $value): static
    {
        $recipients = [];
        foreach ($value as $entry) {
            $recipients[] = ((object) ['email' => $entry]);
        }
        $this->parameterStructured('envelope', 'rcptTo', $recipients);
        return $this;
    }

    public function completionUpdate(string $id, array $actions)
    {
        $this->parameterStructured('onSuccessUpdateEmail', $id, $actions);
        return $this;
    }

    public function completionDestroy(string $id, array $actions)
    {
        $this->parameterStructured('onSuccessDestroyEmail', $id, $actions);
        return $this;
    }

}
