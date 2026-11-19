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

    public function testRelativeLimitIsPositionedFromAnAnchorRecord(): void
    {
        $query = new MailQuery('account');
        $query->limitRelative('M1234', 25, 1);

        $command = json_decode($query->jsonEncode(), true)[1];

        $this->assertSame('M1234', $command['anchor']);
        $this->assertSame(25, $command['limit']);
        $this->assertSame(1, $command['anchorOffset']);
    }

    public function testObjectsKeyedByIdAreEncodedAsMapsWhateverTheIds(): void
    {
        $request = new \JmapClient\Requests\Files\NodeSet('account');
        $request->create('0')->label('created');
        $request->update('0')->label('updated');

        $command = json_decode($request->jsonEncode(), false)[1];

        $this->assertIsObject($command->create);
        $this->assertSame('created', $command->create->{'0'}->name);
        $this->assertIsObject($command->update);
        $this->assertSame('updated', $command->update->{'0'}->name);
    }
}
