<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestSet;

/**
 * @extends RequestSet<MailVacationParameters>
 */
class MailVacationSet extends RequestSet
{
    protected string $_space = 'urn:ietf:params:jmap:vacationresponse';
    protected string $_class = 'VacationResponse';
    protected string $_parametersClass = MailVacationParameters::class;

    /**
     * Update the vacation response (there's only one with id "singleton")
     *
     * @param string $id Vacation response identifier (should be "singleton")
     * @param MailVacationParameters|null $object Vacation parameters object
     *
     * @return MailVacationParameters The vacation parameters for method chaining
     */
    public function update(string $id, mixed $object = null): MailVacationParameters
    {
        return parent::update($id, $object);
    }
}
