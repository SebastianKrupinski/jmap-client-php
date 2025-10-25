<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses\Calendar;

use DateTimeImmutable;
use DateTimeInterface;

class EventParameters extends EventCommonParameters {
    
    /* Metadata Properties */

    public function in(): array|null {
        return array_keys($this->parameter('calendarIds'));
    }
    
    public function id(): string|null {
        return $this->parameter('id');
    }

    public function uid(): string|null {
        return $this->parameter('uid');
    }

    public function method(): string|null {
        return $this->parameter('method');
    }

    public function created(): DateTimeInterface|null {
        $value = $this->parameter('created');
        return ($value) ? new DateTimeImmutable($value) : null;
    }

    public function updated(): DateTimeInterface|null {
        $value = $this->parameter('updated');
        return ($value) ? new DateTimeImmutable($value) : null;
    }

    /* Scheduling Properties */

    public function recurrenceRules(): array {
        $collection = $this->parameter('recurrenceRules') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new EventRecurrenceRuleParameters($data);
        }
        return $collection;
    }

    public function recurrenceExclusions(): array {
        return $this->parameter('excludedRecurrenceRules') ?? [];
    }

    /**
     * @return array<string,EventMutationParameters>
     */
    public function recurrenceMutations(): array {
        $collection = $this->parameter('recurrenceOverrides') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new EventMutationParameters($data);
        }
        return $collection;
    }

}
