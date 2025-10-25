<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses;

use JmapClient\Responses\Response;

class ResponseQueryChanges extends Response {

    public function stateOld(): string {
        return (isset($this->_response[self::RESPONSE_OBJECT]['oldQueryState'])) ? $this->_response[self::RESPONSE_OBJECT]['oldQueryState'] : '';
    }

    public function stateNew(): string {
        return (isset($this->_response[self::RESPONSE_OBJECT]['newQueryState'])) ? $this->_response[self::RESPONSE_OBJECT]['newQueryState'] : '';
    }

    public function created(): array {
        return (isset($this->_response[self::RESPONSE_OBJECT]['created'])) ? $this->_response[self::RESPONSE_OBJECT]['created'] : [];
    }

    public function updated(): array {
        return (isset($this->_response[self::RESPONSE_OBJECT]['updated'])) ? $this->_response[self::RESPONSE_OBJECT]['updated'] : [];
    }

    public function deleted(): array {
        return (isset($this->_response[self::RESPONSE_OBJECT]['removed'])) ? $this->_response[self::RESPONSE_OBJECT]['removed'] : [];
    }

}
