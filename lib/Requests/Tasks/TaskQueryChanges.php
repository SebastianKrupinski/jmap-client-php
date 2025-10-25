<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Tasks;

use JmapClient\Requests\RequestQueryChanges;

class TaskQueryChanges extends RequestQueryChanges {

    protected string $_space = 'urn:ietf:params:jmap:tasks';
    protected string $_class = 'Task';
    protected string $_filterClass = TaskFilter::class;
    protected string $_sortClass = TaskSort::class;

    public function filter(): TaskFilter {
        return parent::filter();
    }

    public function sort(): TaskSort {
        return parent::sort();
    }

}
