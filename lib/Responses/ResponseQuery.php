<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses;

use JmapClient\Responses\Response;

class ResponseQuery extends Response {

    public function state(): string {
        return (isset($this->_response[self::RESPONSE_OBJECT]['queryState'])) ? $this->_response[self::RESPONSE_OBJECT]['queryState'] : '';
    }

    public function list(): array {
        return (isset($this->_response[self::RESPONSE_OBJECT]['ids'])) ? $this->_response[self::RESPONSE_OBJECT]['ids'] : [];
    }

}
