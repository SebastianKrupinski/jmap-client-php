<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Calendar;

use DateTimeZone;
use JmapClient\Requests\RequestQuery;

class EventQuery extends RequestQuery {

    protected string $_space = 'urn:ietf:params:jmap:calendars';
    protected string $_class = 'CalendarEvent';
    protected string $_filterClass = EventFilter::class;
    protected string $_sortClass = EventSort::class;

    public function filter(): EventFilter {
        return parent::filter();
    }

    public function sort(): EventSort {
        return parent::sort();
    }

    public function timezone(DateTimeZone $value): self {

        // creates or updates parameter and assigns new value
        $this->_command['timeZone'] = $value->getName();
        // return self for function chaining 
        return $this;
        
    }

}
