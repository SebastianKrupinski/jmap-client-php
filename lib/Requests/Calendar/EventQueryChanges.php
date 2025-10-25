<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Calendar;

use JmapClient\Requests\RequestQueryChanges;

class EventQueryChanges extends RequestQueryChanges {

    protected string $_space = 'urn:ietf:params:jmap:calendars';
    protected string $_class = 'CalendarEvent';

    public function filter(): EventFilter {
        
        // evaluate if filter parameter exist and create if needed
        if (!isset($this->_command['filter'])) {
            $this->_command['filter'] = new \stdClass();
        }
        // return self for function chaining 
        return new EventFilter($this->_command['filter']);

    }

    public function sort(): EventSort {

        // evaluate if sort parameter exist and create if needed
        if (!isset($this->_command['sort'])) {
            $this->_command['sort'] = [];
        }
        // return self for function chaining 
        return new EventSort($this->_command['sort']);

    }

}
