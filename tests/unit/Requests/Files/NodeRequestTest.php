<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests\Files;

use JmapClient\Requests\Files\NodeCopy;
use JmapClient\Requests\Files\NodeGet;
use JmapClient\Requests\Files\NodeKind;
use JmapClient\Requests\Files\NodePermissions;
use JmapClient\Requests\Files\NodeQuery;
use JmapClient\Requests\Files\NodeSet;
use JmapClient\Requests\RequestBundle;
use PHPUnit\Framework\TestCase;

class NodeRequestTest extends TestCase
{
    private function calls(...$requests): array
    {
        return json_decode((new RequestBundle(...$requests))->jsonEncode(), true, 512, JSON_THROW_ON_ERROR)['methodCalls'];
    }

    public function testGetWithAncestorsSendsFetchParents(): void
    {
        $request = new NodeGet('account-1', 'get-1');
        $request->target('node-1')->withAncestors(true);

        $this->assertSame([
            'FileNode/get',
            [
                'accountId' => 'account-1',
                'ids' => ['node-1'],
                'fetchParents' => true,
            ],
            'get-1',
        ], $this->calls($request)[0]);
    }

    public function testSetCreatesNodesWithTypeAndMediaType(): void
    {
        $request = new NodeSet('account-1', 'set-1');
        $request->compareCaseInsensitively(true)->onExists('rename');
        $request->create('c1')->kind(NodeKind::File)->label('a.txt')->type('text/plain')->blob('blob-1');
        $request->create('c2')->kind(NodeKind::SymLink)->label('link')->target(['', 'docs', 'a.txt']);
        $request->create('c3')->kind(NodeKind::Directory)->label('shared')
            ->sharees('user-2', (new NodePermissions())->read(true)->add(true)->rename(true)->delete(false)->write(true)->share(false));
        $request->create('c3')->sharees('user-3')->read(true);

        $this->assertSame([
            'accountId' => 'account-1',
            'compareCaseInsensitively' => true,
            'onExists' => 'rename',
            'create' => [
                'c1' => ['@type' => 'File', 'name' => 'a.txt', 'mediaType' => 'text/plain', 'blobId' => 'blob-1'],
                'c2' => ['@type' => 'SymLink', 'name' => 'link', 'target' => ['', 'docs', 'a.txt']],
                'c3' => ['@type' => 'Directory', 'name' => 'shared', 'shareWith' => ['user-2' => [
                    'mayRead' => true,
                    'mayAddChildren' => true,
                    'mayRename' => true,
                    'mayDelete' => false,
                    'mayModifyContent' => true,
                    'mayShare' => false,
                ], 'user-3' => [
                    'mayRead' => true,
                ]]],
            ],
        ], $this->calls($request)[0][1]);
    }

    public function testQueryUsesDraftFilterAndSortNames(): void
    {
        $request = new NodeQuery('account-1', 'query-1');
        $request->filter()->descendantOf('folder-1')->kind(NodeKind::File)->formatMatches('image/');
        $request->sort()->kind(true)->label(true);

        $command = $this->calls($request)[0][1];

        $this->assertSame([
            'ancestorId' => 'folder-1',
            'nodeType' => 'File',
            'mediaTypeMatch' => 'image/',
        ], $command['filter']);
        $this->assertSame([
            ['property' => 'nodeType', 'isAscending' => true],
            ['property' => 'name', 'isAscending' => true],
        ], $command['sort']);
    }

    public function testHierarchyFiltersMapToDraftConditions(): void
    {
        $in = new NodeQuery('account-1');
        $in->filter()->in('folder-1');
        $top = new NodeQuery('account-1');
        $top->filter()->in(null);
        $ancestors = new NodeQuery('account-1');
        $ancestors->filter()->ancestorOf('node-1');

        $calls = $this->calls($in, $top, $ancestors);

        $this->assertSame(['parentId' => 'folder-1'], $calls[0][1]['filter']);
        $this->assertSame(['isTopLevel' => true], $calls[1][1]['filter']);
        $this->assertSame(['descendantId' => 'node-1'], $calls[2][1]['filter']);
    }

    public function testCopyDefaultsSourceAccountToDestination(): void
    {
        $request = new NodeCopy('account-1', 'copy-1');
        $request->copy('k1', 'node-1')->in('folder-2')->label('copy.txt');

        $this->assertSame([
            'FileNode/copy',
            [
                'accountId' => 'account-1',
                'fromAccountId' => 'account-1',
                'create' => [
                    'k1' => ['id' => 'node-1', 'parentId' => 'folder-2', 'name' => 'copy.txt'],
                ],
            ],
            'copy-1',
        ], $this->calls($request)[0]);
    }

    public function testCopyBetweenAccountsWithArguments(): void
    {
        $request = new NodeCopy('account-2', 'copy-1');
        $request
            ->sourceAccount('account-1')
            ->sourceState('from-state')
            ->destinationState('to-state')
            ->destroySource(true)
            ->destroySourceState('destroy-state')
            ->destroyRecursive(true)
            ->onExists('replace')
            ->compareCaseInsensitively(false);
        $request->copy('k1', 'node-1');

        $this->assertSame([
            'accountId' => 'account-2',
            'fromAccountId' => 'account-1',
            'ifFromInState' => 'from-state',
            'ifInState' => 'to-state',
            'onSuccessDestroyOriginal' => true,
            'destroyFromIfInState' => 'destroy-state',
            'onDestroyRemoveChildren' => true,
            'onExists' => 'replace',
            'compareCaseInsensitively' => false,
            'create' => ['k1' => ['id' => 'node-1']],
        ], $this->calls($request)[0][1]);
    }

    public function testClonedCopyIsIndependent(): void
    {
        $request = new NodeCopy('account-1', 'copy-1');
        $request->copy('k1', 'node-1');
        $clone = clone $request;
        $clone->copy('k2', 'node-2');

        $this->assertSame(['k1'], array_keys($this->calls($request)[0][1]['create']));
        $this->assertSame(['k1', 'k2'], array_keys($this->calls($clone)[0][1]['create']));
    }
}
