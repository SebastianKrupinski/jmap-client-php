<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Tasks;

use JmapClient\Requests\RequestGet;

class TaskListGet extends RequestGet
{
    protected string $_space = 'urn:ietf:params:jmap:tasks';
    protected string $_class = 'TaskList';

}
