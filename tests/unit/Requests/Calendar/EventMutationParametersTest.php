<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests\Calendar;

use JmapClient\Requests\Calendar\EventMutationParameters;
use PHPUnit\Framework\TestCase;

class EventMutationParametersTest extends TestCase
{
    public function testExcluded(): void
    {
        $parameters = null;
        $mutation = new EventMutationParameters($parameters);
        $mutation->bind($parameters);

        $mutation->excluded(true);

        $this->assertTrue($parameters->excluded);
    }
}
