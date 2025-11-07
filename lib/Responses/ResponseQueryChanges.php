<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses;

class ResponseQueryChanges extends Response
{
    public function stateOld(): string
    {
        return (isset($this->_response[self::RESPONSE_OBJECT]['oldQueryState'])) ? $this->_response[self::RESPONSE_OBJECT]['oldQueryState'] : '';
    }

    public function stateNew(): string
    {
        return (isset($this->_response[self::RESPONSE_OBJECT]['newQueryState'])) ? $this->_response[self::RESPONSE_OBJECT]['newQueryState'] : '';
    }

    public function added(): array
    {
        return (isset($this->_response[self::RESPONSE_OBJECT]['added'])) ? $this->_response[self::RESPONSE_OBJECT]['added'] : [];
    }

    public function updated(): array
    {
        return (isset($this->_response[self::RESPONSE_OBJECT]['updated'])) ? $this->_response[self::RESPONSE_OBJECT]['updated'] : [];
    }

    public function removed(): array
    {
        return (isset($this->_response[self::RESPONSE_OBJECT]['removed'])) ? $this->_response[self::RESPONSE_OBJECT]['removed'] : [];
    }
}
