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


    public function filter(): TaskFilter {
        
        // evaluate if filter parameter exist and create if needed
        if (!isset($this->_command['filter'])) {
            $this->_command['filter'] = new \stdClass();
        }
        // return self for function chaining 
        return new TaskFilter($this->_command['filter']);

    }

    public function sort(): TaskSort {

        // evaluate if sort parameter exist and create if needed
        if (!isset($this->_command['sort'])) {
            $this->_command['sort'] = [];
        }
        // return self for function chaining 
        return new TaskSort($this->_command['sort']);

    }

}
