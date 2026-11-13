<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Files;

use JmapClient\Requests\Files\NodeKind;
use JmapClient\Responses\Files\NodeCopy;
use JmapClient\Responses\Files\NodeGet;
use JmapClient\Responses\ResponseBundle;
use PHPUnit\Framework\TestCase;

class NodeResponseTest extends TestCase
{
    public function testGetDeserializesDraftProperties(): void
    {
        $bundle = new ResponseBundle([
            'methodResponses' => [[
                'FileNode/get',
                [
                    'accountId' => 'account-1',
                    'state' => 'state-1',
                    'list' => [
                        [
                            'id' => 'node-1',
                            '@type' => 'File',
                            'parentId' => 'folder-1',
                            'name' => 'a.txt',
                            'blobId' => 'blob-1',
                            'size' => 12,
                            'mediaType' => 'text/plain',
                            'created' => '2026-11-01T10:00:00Z',
                            'modified' => '2026-11-02T10:00:00Z',
                            'changed' => '2026-11-03T10:00:00Z',
                        ],
                        [
                            'id' => 'node-2',
                            '@type' => 'SymLink',
                            'name' => 'link',
                            'target' => ['', 'docs', 'a.txt'],
                        ],
                        [
                            'id' => 'folder-1',
                            '@type' => 'Directory',
                            'name' => 'docs',
                            'myRights' => [
                                'mayRead' => true,
                                'mayAddChildren' => true,
                                'mayRename' => false,
                                'mayDelete' => false,
                                'mayModifyContent' => true,
                                'mayShare' => false,
                            ],
                        ],
                    ],
                    'notFound' => [],
                ],
                'get-1',
            ]],
            'sessionState' => 'session-1',
        ]);

        $response = $bundle->first();
        $this->assertInstanceOf(NodeGet::class, $response);

        $file = $response->object(0);
        $this->assertSame(NodeKind::File, $file->kind());
        $this->assertSame('text/plain', $file->type());
        $this->assertSame(12, $file->size());
        $this->assertSame('2026-11-02T10:00:00+00:00', $file->modified()->format(DATE_ATOM));
        $this->assertSame('2026-11-03T10:00:00+00:00', $file->changed()->format(DATE_ATOM));
        $this->assertSame('2026-11-01T10:00:00+00:00', $file->created()->format(DATE_ATOM));

        $link = $response->object(1);
        $this->assertSame(NodeKind::SymLink, $link->kind());
        $this->assertSame(['', 'docs', 'a.txt'], $link->target());

        $rights = $response->object(2)->rights();
        $this->assertTrue($rights->read());
        $this->assertTrue($rights->add());
        $this->assertFalse($rights->rename());
        $this->assertFalse($rights->delete());
        $this->assertTrue($rights->write());
        $this->assertFalse($rights->share());
    }

    public function testUnknownKindReturnsNull(): void
    {
        $bundle = new ResponseBundle([
            'methodResponses' => [[
                'FileNode/get',
                [
                    'accountId' => 'account-1',
                    'state' => 'state-1',
                    'list' => [['id' => 'node-1', '@type' => 'Socket', 'name' => 'future']],
                    'notFound' => [],
                ],
                'get-1',
            ]],
            'sessionState' => 'session-1',
        ]);

        $this->assertNull($bundle->first()->object(0)->kind());
    }

    public function testCopyDeserializesCreatedAndDestroyed(): void
    {
        $bundle = new ResponseBundle([
            'methodResponses' => [[
                'FileNode/copy',
                [
                    'fromAccountId' => 'account-1',
                    'accountId' => 'account-2',
                    'oldState' => 'state-1',
                    'newState' => 'state-2',
                    'created' => ['k1' => ['id' => 'node-9']],
                    'notCreated' => ['k2' => ['type' => 'invalidProperties']],
                    'destroyed' => ['node-3'],
                ],
                'copy-1',
            ]],
            'sessionState' => 'session-1',
        ]);

        $response = $bundle->first();
        $this->assertInstanceOf(NodeCopy::class, $response);
        $this->assertSame('account-1', $response->sourceAccount());
        $this->assertSame('account-2', $response->account());
        $this->assertSame('state-2', $response->stateNew());
        $this->assertSame(['id' => 'node-9'], $response->createSuccess('k1'));
        $this->assertSame('invalidProperties', $response->createFailure('k2')['type']);
        $this->assertSame(['node-3'], $response->deleteSuccesses());
        $this->assertTrue($response->failures());
    }
}
