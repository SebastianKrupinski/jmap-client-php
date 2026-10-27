<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests;

use JmapClient\Requests\Mail\MailGet;
use JmapClient\Requests\Mail\MailQuery;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testCloneCopiesTheCommand(): void
    {
        $original = new MailQuery('account');
        $original->filter()->in('inbox');
        $original->sort()->received(false);

        $clone = clone $original;

        $this->assertSame($original->jsonSerialize()[0], $clone->jsonSerialize()[0]);
        $this->assertEquals($original->jsonSerialize()[1], $clone->jsonSerialize()[1]);
    }

    public function testCloneAssignsANewIdentifier(): void
    {
        $original = new MailGet('account');

        $clone = clone $original;

        $this->assertNotSame($original->getIdentifier(), $clone->getIdentifier());
        $this->assertSame($clone->getIdentifier(), $clone->jsonSerialize()[2]);
    }

    public function testModifyingTheCloneLeavesTheOriginalUnchanged(): void
    {
        $original = new MailQuery('account');
        $original->filter()->in('inbox');
        $original->sort()->received(false);
        $expected = $original->jsonEncode();

        $clone = clone $original;
        $clone->filter()->in('archive');
        $clone->sort()->subject(true);
        $clone->limitAbsolute(500, 100);

        $this->assertSame($expected, $original->jsonEncode());
        $this->assertNotSame($expected, $clone->jsonEncode());
    }

    public function testModifyingTheOriginalLeavesTheCloneUnchanged(): void
    {
        $original = new MailGet('account');
        $original->target('a');
        $source = new MailQuery('account');
        $original->targetFromRequest($source, '/ids');

        $clone = clone $original;
        $expected = $clone->jsonEncode();
        $original->target('b');
        $original->targetFromRequest(new MailQuery('account'), '/other');

        $this->assertSame($expected, $clone->jsonEncode());
    }
}
