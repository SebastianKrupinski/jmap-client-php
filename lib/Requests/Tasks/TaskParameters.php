<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Tasks;

use DateTime;
use DateTimeImmutable;
use JmapClient\Requests\Request;
use JmapClient\Requests\RequestParameters;

class TaskParameters extends RequestParameters
{
    public const DATE_FORMAT_LOCAL = Request::DATE_FORMAT_LOCAL;
    public const DATE_FORMAT_UTC = Request::DATE_FORMAT_UTC;

    public function __construct(&$parameters = null) {

        parent::__construct($parameters);

    }

    public function type(): string {

        return 'application/jstask+json;type=task';

    }

    /* Metadata Properties */

    public function in(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('taskListId', $value, true);
        // return self for function chaining
        return $this;

    }

    public function uid(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('uid', $value);
        // return self for function chaining
        return $this;

    }

    public function created(DateTime|DateTimeImmutable $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('created', $value->format(self::DATE_FORMAT_UTC));
        // return self for function chaining
        return $this;

    }

    public function updated(DateTime|DateTimeImmutable $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('updated', $value->format(self::DATE_FORMAT_UTC));
        // return self for function chaining
        return $this;

    }

}
