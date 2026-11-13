<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Integration\Files;

use JmapClient\Client;
use JmapClient\Enums\Files\NodeKind;
use JmapClient\Requests\Files\NodeCopy as NodeCopyRequest;
use JmapClient\Requests\Files\NodeGet as NodeGetRequest;
use JmapClient\Requests\Files\NodeQuery as NodeQueryRequest;
use JmapClient\Requests\Files\NodeSet as NodeSetRequest;
use JmapClient\Responses\Files\NodeCopy as NodeCopyResponse;
use JmapClient\Responses\Files\NodeGet as NodeGetResponse;
use JmapClient\Responses\Files\NodeParameters;
use JmapClient\Responses\Files\NodeQuery as NodeQueryResponse;
use JmapClient\Responses\Files\NodeSet as NodeSetResponse;
use JmapClient\Responses\ResponseException;
use JmapClient\Session\Account;
use JmapClient\Tests\Integration\ClientFactory;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    private Client $client;
    private Account $account;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = ClientFactory::instance('service1');
        $this->client->connect();
        $this->account = $this->client->sessionAccountDefault('filenode');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        // construct list request
        $r0 = new NodeGetRequest($this->account->id());
        // transceive
        $bundle = $this->client->perform([$r0]);
        $response = $bundle->first();
        if (!($response instanceof NodeGetResponse)) {
            return;
        }
        $commands = [];
        // construct deletion requests
        foreach ($response->objects() as $node) {
            if (str_starts_with((string) $node->label(), 'Test File Node ') ||
                str_starts_with((string) $node->label(), 'Modified File Node ') ||
                str_starts_with((string) $node->label(), 'To Delete File Node ') ||
                str_starts_with((string) $node->label(), 'Parent File Node ') ||
                str_starts_with((string) $node->label(), 'Child File Node ')) {
                $rq = new NodeSetRequest($this->account->id());
                $rq->destroyRecursive(true);
                $rq->delete($node->id());
                $commands[] = $rq;
            }
        }
        // transceive
        if (!empty($commands)) {
            $this->client->perform($commands);
        }
    }

    public function testNodeSetCreate(): void
    {
        // construct create request
        $createId = uniqid();
        $r0 = new NodeSetRequest($this->account->id());
        $p0 = $r0->create($createId);
        $p0->kind(NodeKind::Directory);
        $p0->label('Test File Node ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify create response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeSetResponse::class, $bundle->first());
        $result = $bundle->first()->createSuccess($createId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testNodeSetCreateWithTextContent(): void
    {
        // upload plain text content via the standard JMAP upload endpoint
        $content = 'Hello, JMAP!';
        $uploadResponse = json_decode($this->client->upload($this->account->id(), 'text/plain', $content), true);
        $this->assertArrayHasKey('blobId', $uploadResponse, 'Upload did not return a blobId');
        $blobId = $uploadResponse['blobId'];

        // create a file node pointing to the blob
        $nodeId = uniqid();
        $r1 = new NodeSetRequest($this->account->id());
        $p1 = $r1->create($nodeId);
        $p1->kind(NodeKind::File);
        $p1->label('Test File Node ' . time());
        $p1->type('text/plain');
        $p1->blob($blobId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify node was created
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeSetResponse::class, $bundle->first());
        $nodeResult = $bundle->first()->createSuccess($nodeId);
        $this->assertNotNull($nodeResult);
        $this->assertArrayHasKey('id', $nodeResult);
    }

    public function testNodeSetCreateWithBinaryContent(): void
    {
        // upload raw binary content via the standard JMAP upload endpoint
        $content = "\x00\x01\x02\x03binary data\xFF\xFE";
        $uploadResponse = json_decode($this->client->upload($this->account->id(), 'application/octet-stream', $content), true);
        $this->assertArrayHasKey('blobId', $uploadResponse, 'Upload did not return a blobId');
        $blobId = $uploadResponse['blobId'];

        // create a file node pointing to the blob
        $nodeId = uniqid();
        $r1 = new NodeSetRequest($this->account->id());
        $p1 = $r1->create($nodeId);
        $p1->kind(NodeKind::File);
        $p1->label('Test File Node ' . time());
        $p1->type('application/octet-stream');
        $p1->blob($blobId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify node was created
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeSetResponse::class, $bundle->first());
        $nodeResult = $bundle->first()->createSuccess($nodeId);
        $this->assertNotNull($nodeResult);
        $this->assertArrayHasKey('id', $nodeResult);
    }

    public function testNodeGetWithBlobContent(): void
    {
        // upload plain text content via the standard JMAP upload endpoint
        $content = 'Hello, JMAP!';
        $uploadResponse = json_decode($this->client->upload($this->account->id(), 'text/plain', $content), true);
        $blobId = $uploadResponse['blobId'];

        // create a file node pointing to the blob
        $nodeId = uniqid();
        $nodeLabel = 'Test File Node ' . time();
        $r1 = new NodeSetRequest($this->account->id());
        $p1 = $r1->create($nodeId);
        $p1->kind(NodeKind::File);
        $p1->label($nodeLabel);
        $p1->type('text/plain');
        $p1->blob($blobId);

        // transceive
        $bundle = $this->client->perform([$r1]);
        $nodeId = $bundle->first()->createSuccess($nodeId)['id'];

        // fetch the node and verify blob properties — request non-default fields explicitly
        $r2 = new NodeGetRequest($this->account->id());
        $r2->target($nodeId);
        $r2->property('id', 'name', 'blobId', 'mediaType', 'size');

        // transceive
        $bundle = $this->client->perform([$r2]);

        // verify get response includes blob metadata
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(NodeParameters::class, $result);
        $this->assertEquals($nodeId, $result->id());
        $this->assertEquals($nodeLabel, $result->label());
        $this->assertNotEmpty($result->blob());
        $this->assertEquals('text/plain', $result->type());
        $this->assertGreaterThan(0, $result->size());
    }

    public function testNodeSetUpdate(): void
    {
        // construct create request
        $nodeId = uniqid();
        $r0 = new NodeSetRequest($this->account->id());
        $p0 = $r0->create($nodeId);
        $p0->kind(NodeKind::Directory);
        $p0->label('Test File Node ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract node id
        $result = $bundle->first()->createSuccess($nodeId);
        $nodeId = $result['id'];

        // construct modify request
        $r1 = new NodeSetRequest($this->account->id());
        $p1 = $r1->update($nodeId);
        $p1->label('Modified File Node ' . time());

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify modify response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeSetResponse::class, $bundle->first());
        $result = $bundle->first()->updateSuccess($nodeId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testNodeSetUpdateContent(): void
    {
        // upload initial content via the standard JMAP upload endpoint
        $initialContent = 'Initial content';
        $uploadResponse = json_decode($this->client->upload($this->account->id(), 'text/plain', $initialContent), true);
        $initialBlobId = $uploadResponse['blobId'];

        // create file node with initial blob
        $nodeId = uniqid();
        $r1 = new NodeSetRequest($this->account->id());
        $p1 = $r1->create($nodeId);
        $p1->kind(NodeKind::File);
        $p1->label('Test File Node ' . time());
        $p1->type('text/plain');
        $p1->blob($initialBlobId);

        // transceive
        $bundle = $this->client->perform([$r1]);
        $nodeId = $bundle->first()->createSuccess($nodeId)['id'];

        // upload replacement content
        $updatedContent = 'Updated content';
        $uploadResponse = json_decode($this->client->upload($this->account->id(), 'text/plain', $updatedContent), true);
        $updatedBlobId = $uploadResponse['blobId'];

        // update the node to point to the new blob
        $r3 = new NodeSetRequest($this->account->id());
        $p3 = $r3->update($nodeId);
        $p3->blob($updatedBlobId);

        // transceive
        $bundle = $this->client->perform([$r3]);

        // verify update response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeSetResponse::class, $bundle->first());
        $this->assertNotNull($bundle->first()->updateSuccess($nodeId));
    }

    public function testNodeSetDelete(): void
    {
        // construct create request
        $nodeId = uniqid();
        $r0 = new NodeSetRequest($this->account->id());
        $p0 = $r0->create($nodeId);
        $p0->kind(NodeKind::Directory);
        $p0->label('To Delete File Node ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract node id
        $result = $bundle->first()->createSuccess($nodeId);
        $nodeId = $result['id'];

        // construct delete request
        $r1 = new NodeSetRequest($this->account->id());
        $r1->delete($nodeId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify delete response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeSetResponse::class, $bundle->first());
        $result = $bundle->first()->deleteSuccess($nodeId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testNodeSetDestroyRecursive(): void
    {
        // construct parent create request
        $parentId = uniqid();
        $r0 = new NodeSetRequest($this->account->id());
        $p0 = $r0->create($parentId);
        $p0->kind(NodeKind::Directory);
        $p0->label('Parent File Node ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract parent node id
        $result = $bundle->first()->createSuccess($parentId);
        $parentId = $result['id'];

        // construct child create request
        $childId = uniqid();
        $r1 = new NodeSetRequest($this->account->id());
        $p1 = $r1->create($childId);
        $p1->kind(NodeKind::Directory);
        $p1->label('Child File Node ' . time());
        $p1->in($parentId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        $result = $bundle->first()->createSuccess($childId);
        $this->assertNotNull($result);

        // delete parent with destroyRecursive enabled
        $r2 = new NodeSetRequest($this->account->id());
        $r2->destroyRecursive(true);
        $r2->delete($parentId);

        // transceive
        $bundle = $this->client->perform([$r2]);

        // verify delete response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeSetResponse::class, $bundle->first());
        $result = $bundle->first()->deleteSuccess($parentId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testNodeCopy(): void
    {
        // construct create request for the directory to copy
        $sourceId = uniqid();
        $r0 = new NodeSetRequest($this->account->id());
        $p0 = $r0->create($sourceId);
        $p0->kind(NodeKind::Directory);
        $p0->label('Parent File Node ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);
        $sourceId = $bundle->first()->createSuccess($sourceId)['id'];

        // construct copy request within the same account under a new name
        $copyId = uniqid();
        $copyLabel = 'Test File Node Copy ' . time();
        $r1 = new NodeCopyRequest($this->account->id());
        $r1->copy($copyId, $sourceId)->label($copyLabel);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // draft-ietf-jmap-filenode allows copying within one account, some servers only copy between accounts
        $response = $bundle->first();
        if ($response instanceof ResponseException && $response->type() === ResponseException::INVALID_ARGUMENTS) {
            $this->markTestSkipped('Server does not support FileNode/copy within one account: ' . $response->description());
        }

        // verify copy response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeCopyResponse::class, $bundle->first());
        $result = $bundle->first()->createSuccess($copyId);
        $this->assertNotNull($result, json_encode($bundle->first()->createFailure($copyId)));
        $this->assertArrayHasKey('id', $result);
        $this->assertNotEquals($sourceId, $result['id']);

        // verify the copy exists with the new name
        $r2 = new NodeGetRequest($this->account->id());
        $r2->target($result['id']);
        $bundle = $this->client->perform([$r2]);
        $node = $bundle->first()->object(0);
        $this->assertEquals($copyLabel, $node->label());
        $this->assertSame(NodeKind::Directory, $node->kind());
    }

    public function testNodeCopyBetweenAccounts(): void
    {
        // connect as the second user, who owns the source node
        $owner = ClientFactory::instance('service2');
        $owner->connect();
        $ownerAccount = $owner->sessionAccountDefault('filenode');
        $this->assertNotNull($ownerAccount, 'Second user has no FileNode account');

        // construct create request for a directory shared read only with the first user
        $sourceId = uniqid();
        $r0 = new NodeSetRequest($ownerAccount->id());
        $p0 = $r0->create($sourceId);
        $p0->kind(NodeKind::Directory);
        $p0->label('Parent File Node ' . time());
        $p0->sharees($this->account->id())->read(true);

        // transceive
        $bundle = $owner->perform([$r0]);
        $result = $bundle->first()->createSuccess($sourceId);
        $this->assertNotNull($result, json_encode($bundle->first()->createFailure($sourceId)));
        $sourceId = $result['id'];

        try {
            // reconnect as the first user, so the session lists the shared account
            $client = ClientFactory::instance('service1');
            $client->connect();
            $sharedAccount = $client->sessionAccounts()?->account($ownerAccount->id());
            $this->assertNotNull($sharedAccount, 'Shared account is not listed in the session of the first user');
            $this->assertFalse($sharedAccount->personal());

            // construct copy request from the shared account into the personal account
            $copyId = uniqid();
            $copyLabel = 'Test File Node Copy ' . time();
            $r1 = new NodeCopyRequest($this->account->id());
            $r1->sourceAccount($sharedAccount->id());
            $r1->copy($copyId, $sourceId)->label($copyLabel);

            // transceive
            $bundle = $client->perform([$r1]);

            // verify copy response
            $this->assertEquals(1, $bundle->tally());
            $response = $bundle->first();
            $this->assertInstanceOf(NodeCopyResponse::class, $response, $response instanceof ResponseException ? $response->type() . ': ' . $response->description() : '');
            $this->assertEquals($sharedAccount->id(), $response->sourceAccount());
            $this->assertEquals($this->account->id(), $response->account());
            $result = $response->createSuccess($copyId);
            $this->assertNotNull($result, json_encode($response->createFailure($copyId)));
            $this->assertArrayHasKey('id', $result);

            // verify the copy exists in the personal account with the new name
            $r2 = new NodeGetRequest($this->account->id());
            $r2->target($result['id']);
            $bundle = $client->perform([$r2]);
            $node = $bundle->first()->object(0);
            $this->assertEquals($copyLabel, $node->label());
            $this->assertSame(NodeKind::Directory, $node->kind());
        } finally {
            // remove the source node from the second user's account
            $r3 = new NodeSetRequest($ownerAccount->id());
            $r3->destroyRecursive(true);
            $r3->delete($sourceId);
            $owner->perform([$r3]);
        }
    }

    public function testNodeGetSpecific(): void
    {
        // construct create request
        $nodeId = uniqid();
        $nodeLabel = 'Test File Node ' . time();
        $r0 = new NodeSetRequest($this->account->id());
        $p0 = $r0->create($nodeId);
        $p0->kind(NodeKind::Directory);
        $p0->label($nodeLabel);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract node id
        $result = $bundle->first()->createSuccess($nodeId);
        $nodeId = $result['id'];

        // construct fetch request
        $r1 = new NodeGetRequest($this->account->id());
        $r1->target($nodeId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(NodeParameters::class, $result);
        $this->assertEquals($nodeId, $result->id());
        $this->assertEquals($nodeLabel, $result->label());
    }

    public function testNodeGetAll(): void
    {
        // construct create request
        $createId = uniqid();
        $r0 = new NodeSetRequest($this->account->id());
        $p0 = $r0->create($createId);
        $p0->kind(NodeKind::Directory);
        $p0->label('Test File Node ' . time());

        // transceive
        $this->client->perform([$r0]);

        // construct fetch request
        $r1 = new NodeGetRequest($this->account->id());

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeGetResponse::class, $bundle->first());
        $results = $bundle->first()->objects();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        foreach ($results as $result) {
            $this->assertInstanceOf(NodeParameters::class, $result);
        }
    }

    public function testNodeGetWithProperties(): void
    {
        // construct create request
        $createId = uniqid();
        $r0 = new NodeSetRequest($this->account->id());
        $p0 = $r0->create($createId);
        $p0->kind(NodeKind::Directory);
        $p0->label('Test File Node ' . time());

        // transceive
        $this->client->perform([$r0]);

        // construct fetch request with limited properties
        $r1 = new NodeGetRequest($this->account->id());
        $r1->property('id', 'name');

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify fetch response — only id and name should be populated
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(NodeParameters::class, $result);
        $this->assertNotEmpty($result->id());
        $this->assertNotEmpty($result->label());
        $this->assertNull($result->role());
    }

    private function skipIfQueryUnsupported(): void
    {
        $capability = $this->account->capability('urn:ietf:params:jmap:filenode');
        if ($capability === null || $capability->empty()) {
            $this->markTestSkipped('Server does not advertise FileNode query capabilities.');
        }
        // An empty fileNodeQuerySortOptions list indicates an incomplete query implementation
        $sortOptions = $capability->getProperty('fileNodeQuerySortOptions', null);
        if ($sortOptions !== null && empty($sortOptions)) {
            $this->markTestSkipped('Server FileNode/query implementation is incomplete (fileNodeQuerySortOptions is empty).');
        }
    }

    private function skipIfSortUnsupported(): void
    {
        $capability = $this->account->capability('urn:ietf:params:jmap:filenode');
        $sortOptions = $capability?->getProperty('fileNodeQuerySortOptions', []);
        if (empty($sortOptions)) {
            $this->markTestSkipped('Server does not support FileNode/query sort (fileNodeQuerySortOptions is empty).');
        }
    }

    public function testNodeQueryWithParentFilter(): void
    {
        $this->skipIfQueryUnsupported();

        // construct parent create request
        $parentId = uniqid();
        $r0 = new NodeSetRequest($this->account->id());
        $p0 = $r0->create($parentId);
        $p0->kind(NodeKind::Directory);
        $p0->label('Parent File Node ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);
        $parentId = $bundle->first()->createSuccess($parentId)['id'];

        // construct child create request
        $childId = uniqid();
        $r1 = new NodeSetRequest($this->account->id());
        $p1 = $r1->create($childId);
        $p1->kind(NodeKind::Directory);
        $p1->label('Child File Node ' . time());
        $p1->in($parentId);

        // transceive
        $bundle = $this->client->perform([$r1]);
        $childId = $bundle->first()->createSuccess($childId)['id'];

        // construct query request scoped to parent via filter.parentId (RFC §3.2.5)
        $r2 = new NodeQueryRequest($this->account->id());
        $r2->filter()->in($parentId);

        // transceive
        $bundle = $this->client->perform([$r2]);

        // verify query response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeQueryResponse::class, $bundle->first());
        $results = $bundle->first()->list();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        $this->assertContains($childId, $results);
    }

    public function testNodeQueryWithMatchingFilter(): void
    {
        $this->skipIfQueryUnsupported();

        // construct parent create request
        $parentId = uniqid();
        $r0 = new NodeSetRequest($this->account->id());
        $p0 = $r0->create($parentId);
        $p0->kind(NodeKind::Directory);
        $p0->label('Parent File Node ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);
        $parentId = $bundle->first()->createSuccess($parentId)['id'];

        // construct child create request with known name
        $childLabel = 'Child File Node ' . time();
        $childId = uniqid();
        $r1 = new NodeSetRequest($this->account->id());
        $p1 = $r1->create($childId);
        $p1->kind(NodeKind::Directory);
        $p1->label($childLabel);
        $p1->in($parentId);

        // transceive
        $bundle = $this->client->perform([$r1]);
        $childId = $bundle->first()->createSuccess($childId)['id'];

        // construct query request scoped to parent with exact label filter
        $r2 = new NodeQueryRequest($this->account->id());
        $r2->filter()->in($parentId)->labelIs($childLabel);

        // transceive
        $bundle = $this->client->perform([$r2]);

        // verify query response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeQueryResponse::class, $bundle->first());
        $results = $bundle->first()->list();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        $this->assertCount(1, $results);
        $this->assertContains($childId, $results);
    }

    public function testNodeQueryWithNonMatchingFilter(): void
    {
        $this->skipIfQueryUnsupported();

        // construct parent create request
        $parentId = uniqid();
        $r0 = new NodeSetRequest($this->account->id());
        $p0 = $r0->create($parentId);
        $p0->kind(NodeKind::Directory);
        $p0->label('Parent File Node ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);
        $parentId = $bundle->first()->createSuccess($parentId)['id'];

        // construct query request scoped to parent with non-matching label
        $r1 = new NodeQueryRequest($this->account->id());
        $r1->filter()->in($parentId)->labelIs('This File Node Does Not Exist ' . uniqid());

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify query response returns empty results
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeQueryResponse::class, $bundle->first());
        $results = $bundle->first()->list();
        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    public function testNodeQueryWithScopeIsolation(): void
    {
        $this->skipIfQueryUnsupported();

        // construct grandparent create request
        $grandparentId = uniqid();
        $r0 = new NodeSetRequest($this->account->id());
        $p0 = $r0->create($grandparentId);
        $p0->kind(NodeKind::Directory);
        $p0->label('Parent File Node ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);
        $grandparentId = $bundle->first()->createSuccess($grandparentId)['id'];

        // construct parent (child of grandparent) create request
        $parentId = uniqid();
        $r1 = new NodeSetRequest($this->account->id());
        $p1 = $r1->create($parentId);
        $p1->kind(NodeKind::Directory);
        $p1->label('Child File Node ' . time());
        $p1->in($grandparentId);

        // transceive
        $bundle = $this->client->perform([$r1]);
        $parentId = $bundle->first()->createSuccess($parentId)['id'];

        // construct grandchild (child of parent) create request
        $grandchildId = uniqid();
        $r2 = new NodeSetRequest($this->account->id());
        $p2 = $r2->create($grandchildId);
        $p2->kind(NodeKind::Directory);
        $p2->label('Child File Node ' . time());
        $p2->in($parentId);

        // transceive
        $this->client->perform([$r2]);

        // query scoped to grandparent — filter.parentId is not recursive, direct children only
        $r3 = new NodeQueryRequest($this->account->id());
        $r3->filter()->in($grandparentId);

        // transceive
        $bundle = $this->client->perform([$r3]);

        // verify direct child appears but grandchild does not
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeQueryResponse::class, $bundle->first());
        $results = $bundle->first()->list();
        $this->assertIsArray($results);
        $this->assertContains($parentId, $results);
        $this->assertNotContains($grandchildId, $results);
    }

    public function testNodeQueryWithChildFilter(): void
    {
        $this->skipIfQueryUnsupported();

        // construct parent create request
        $parentId = uniqid();
        $r0 = new NodeSetRequest($this->account->id());
        $p0 = $r0->create($parentId);
        $p0->kind(NodeKind::Directory);
        $p0->label('Parent File Node ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);
        $parentId = $bundle->first()->createSuccess($parentId)['id'];

        // construct child create request
        $childId = uniqid();
        $r1 = new NodeSetRequest($this->account->id());
        $p1 = $r1->create($childId);
        $p1->kind(NodeKind::Directory);
        $p1->label('Child File Node ' . time());
        $p1->in($parentId);

        // transceive
        $bundle = $this->client->perform([$r1]);
        $childId = $bundle->first()->createSuccess($childId)['id'];

        // query with filter.parentId — direct child must appear
        $r2 = new NodeQueryRequest($this->account->id());
        $r2->filter()->in($parentId);

        // transceive
        $bundle = $this->client->perform([$r2]);

        // verify query response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(NodeQueryResponse::class, $bundle->first());
        $results = $bundle->first()->list();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        $this->assertContains($childId, $results);
    }

    public function testNodeQueryWithSorting(): void
    {
        $this->skipIfQueryUnsupported();
        $this->skipIfSortUnsupported();

        // construct parent create request
        $parentId = uniqid();
        $r0 = new NodeSetRequest($this->account->id());
        $p0 = $r0->create($parentId);
        $p0->kind(NodeKind::Directory);
        $p0->label('Parent File Node ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);
        $parentId = $bundle->first()->createSuccess($parentId)['id'];

        // construct two children with sortable names
        $child1Id = uniqid();
        $r1 = new NodeSetRequest($this->account->id());
        $p1 = $r1->create($child1Id);
        $p1->kind(NodeKind::Directory);
        $p1->label('Child File Node AAA');
        $p1->in($parentId);

        $bundle = $this->client->perform([$r1]);
        $child1Id = $bundle->first()->createSuccess($child1Id)['id'];

        $child2Id = uniqid();
        $r2 = new NodeSetRequest($this->account->id());
        $p2 = $r2->create($child2Id);
        $p2->kind(NodeKind::Directory);
        $p2->label('Child File Node ZZZ');
        $p2->in($parentId);

        $bundle = $this->client->perform([$r2]);
        $child2Id = $bundle->first()->createSuccess($child2Id)['id'];

        // construct ascending sort query scoped to parent
        $r3 = new NodeQueryRequest($this->account->id());
        $r3->filter()->in($parentId);
        $r3->sort()->label(true);

        // transceive ascending query
        $bundleAscending = $this->client->perform([$r3]);

        // verify ascending fetch response
        $this->assertEquals(1, $bundleAscending->tally());
        $this->assertInstanceOf(NodeQueryResponse::class, $bundleAscending->first());
        $ascendingResults = $bundleAscending->first()->list();
        $this->assertIsArray($ascendingResults);
        $this->assertCount(2, $ascendingResults);

        // construct descending sort query scoped to parent
        $r4 = new NodeQueryRequest($this->account->id());
        $r4->filter()->in($parentId);
        $r4->sort()->label(false);

        // transceive descending query
        $bundleDescending = $this->client->perform([$r4]);

        // verify descending fetch response
        $this->assertEquals(1, $bundleDescending->tally());
        $this->assertInstanceOf(NodeQueryResponse::class, $bundleDescending->first());
        $descendingResults = $bundleDescending->first()->list();
        $this->assertIsArray($descendingResults);
        $this->assertCount(2, $descendingResults);

        // AAA sorts first ascending → last descending
        $this->assertSame($child1Id, $ascendingResults[0]);
        $this->assertSame($child1Id, $descendingResults[1]);
    }
}
