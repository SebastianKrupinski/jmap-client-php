<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Tasks;

use JmapClient\Responses\Tasks\TaskParameters;
use PHPUnit\Framework\TestCase;

class TaskParametersTest extends TestCase
{
    public function testIn(): void
    {
        $parameters = new TaskParameters([
            'taskListId' => ['list-1' => true, 'list-2' => true],
        ]);

        $this->assertSame(['list-1', 'list-2'], $parameters->in());
    }

    public function testInMissing(): void
    {
        $this->assertNull((new TaskParameters([]))->in());
    }
}
