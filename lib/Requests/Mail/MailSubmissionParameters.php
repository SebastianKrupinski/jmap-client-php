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
    public function __construct(&$parameters = null) {

        parent::__construct($parameters);

    }

    public function identity(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('identityId', $value);
        // return self for function chaining
        return $this;

    }

    public function message(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('emailId', $value);
        // return self for function chaining
        return $this;

    }

    public function from(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('envelope', 'mailFrom', (object) ['email' => $value]);
        // return self for function chaining
        return $this;

    }

    public function to(array $value): self {
        
        // creates or updates parameter and assigns value
        $recipients = [];
        foreach ($value as $entry) {
            $recipients[] = ((object) ['email' => $entry]);
        }
        $this->parameterStructured('envelope', 'rcptTo', $recipients);
        // return self for function chaining
        return $this;

    }

    public function completionUpdate(string $id, array $actions) {

        // creates or updates parameter and assigns value
        $this->parameterStructured('onSuccessUpdateEmail', $id, $actions);
        // return self for function chaining
        return $this;

    }

    public function completionDestroy(string $id, array $actions) {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('onSuccessDestroyEmail', $id, $actions);
        // return self for function chaining
        return $this;

    }

}
