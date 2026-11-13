<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Integration\Sieve;

use JmapClient\Client;
use JmapClient\Requests\Sieve\ScriptGet as ScriptGetRequest;
use JmapClient\Requests\Sieve\ScriptQuery as ScriptQueryRequest;
use JmapClient\Requests\Sieve\ScriptSet as ScriptSetRequest;
use JmapClient\Requests\Sieve\ScriptValidate as ScriptValidateRequest;
use JmapClient\Responses\ResponseException;
use JmapClient\Responses\Sieve\ScriptGet as ScriptGetResponse;
use JmapClient\Responses\Sieve\ScriptParameters as ScriptParametersResponse;
use JmapClient\Responses\Sieve\ScriptQuery as ScriptQueryResponse;
use JmapClient\Responses\Sieve\ScriptSet as ScriptSetResponse;
use JmapClient\Responses\Sieve\ScriptValidate as ScriptValidateResponse;
use JmapClient\Session\Account;
use JmapClient\Tests\Integration\ClientFactory;
use PHPUnit\Framework\TestCase;

class ScriptTest extends TestCase
{
    private const VALID_SCRIPT = "require [\"fileinto\"];\nif header :contains \"subject\" \"[test]\" {\n    fileinto \"INBOX\";\n}\n";
    private const INVALID_SCRIPT = "require [\"fileinto\"];\nnot_a_command;\n";

    private Client $client;
    private Account $account;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = ClientFactory::instance('service1');
        $this->client->connect();
        $this->account = $this->client->sessionAccountDefault('sieve');
        $this->assertNotNull($this->account, 'Server does not advertise urn:ietf:params:jmap:sieve');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        // deactivate first, an active script can not be deleted
        $r0 = new ScriptGetRequest($this->account->id());
        $bundle = $this->client->perform([$r0]);
        $response = $bundle->first();
        if (!($response instanceof ScriptGetResponse)) {
            return;
        }
        $scripts = array_filter($response->objects(), fn ($script) => str_starts_with((string) $script->label(), 'Test Script '));
        if ($scripts === []) {
            return;
        }
        // only deactivate when a test script is the active one, other scripts such as vacation are left alone
        if (array_filter($scripts, fn ($script) => $script->active()) !== []) {
            $r1 = new ScriptSetRequest($this->account->id());
            $r1->deactivate();
            $this->client->perform([$r1]);
        }
        $r2 = new ScriptSetRequest($this->account->id());
        foreach ($scripts as $script) {
            $r2->delete($script->id());
        }
        $this->client->perform([$r2]);
    }

    private function upload(string $content): string
    {
        $result = json_decode($this->client->upload($this->account->id(), 'application/sieve', $content), true);
        $this->assertArrayHasKey('blobId', $result, 'Upload did not return a blobId');
        return $result['blobId'];
    }

    private function describe(mixed $response): string
    {
        return $response instanceof ResponseException ? $response->type() . ': ' . $response->description() : '';
    }

    private function createScript(string $label, bool $activate = false): string
    {
        $createId = uniqid();
        $r0 = new ScriptSetRequest($this->account->id());
        $r0->create($createId)->label($label)->blob($this->upload(self::VALID_SCRIPT));
        if ($activate) {
            $r0->activate('#' . $createId);
        }

        $bundle = $this->client->perform([$r0]);
        $response = $bundle->first();
        $this->assertInstanceOf(ScriptSetResponse::class, $response, $this->describe($response));
        $result = $response->createSuccess($createId);
        $this->assertNotNull($result, json_encode($response->createFailure($createId)));
        return $result['id'];
    }

    private function fetch(string $id): ScriptParametersResponse
    {
        $r0 = new ScriptGetRequest($this->account->id());
        $r0->target($id);
        $bundle = $this->client->perform([$r0]);
        $this->assertInstanceOf(ScriptGetResponse::class, $bundle->first(), $this->describe($bundle->first()));
        return $bundle->first()->object(0);
    }

    public function testScriptValidate(): void
    {
        $r0 = new ScriptValidateRequest($this->account->id());
        $r0->blob($this->upload(self::VALID_SCRIPT));
        $r1 = new ScriptValidateRequest($this->account->id());
        $r1->blob($this->upload(self::INVALID_SCRIPT));

        $bundle = $this->client->perform([$r0, $r1]);

        $valid = $bundle->response(0);
        $this->assertInstanceOf(ScriptValidateResponse::class, $valid, $this->describe($valid));
        $this->assertTrue($valid->valid(), json_encode($valid->error()));
        $invalid = $bundle->response(1);
        $this->assertInstanceOf(ScriptValidateResponse::class, $invalid, $this->describe($invalid));
        $this->assertFalse($invalid->valid());
        $this->assertSame('invalidScript', $invalid->errorType());
    }

    public function testScriptCreateActivatesAndReadsBack(): void
    {
        $label = 'Test Script Active ' . time();
        $id = $this->createScript($label, true);

        $script = $this->fetch($id);
        $this->assertSame($label, $script->label());
        $this->assertNotEmpty($script->blob());
        $this->assertTrue($script->active());

        // the stored content matches the upload
        $content = '';
        $this->client->download($this->account->id(), $script->blob(), $content, 'application/sieve', 'script.siv');
        $this->assertSame(self::VALID_SCRIPT, $content);
    }

    public function testScriptUpdateAndDeactivate(): void
    {
        $id = $this->createScript('Test Script Update ' . time(), true);

        // rename the script and deactivate it
        $label = 'Test Script Renamed ' . time();
        $r0 = new ScriptSetRequest($this->account->id());
        $r0->update($id)->label($label);
        $r0->deactivate();
        $bundle = $this->client->perform([$r0]);
        $response = $bundle->first();
        $this->assertNotNull($response->updateSuccess($id), json_encode($response->updateFailure($id)));

        $script = $this->fetch($id);
        $this->assertSame($label, $script->label());
        $this->assertFalse($script->active());
    }

    public function testScriptDelete(): void
    {
        $id = $this->createScript('Test Script Delete ' . time());

        $r0 = new ScriptSetRequest($this->account->id());
        $r0->delete($id);
        $bundle = $this->client->perform([$r0]);

        $this->assertNotNull($bundle->first()->deleteSuccess($id), json_encode($bundle->first()->deleteFailure($id)));
    }

    public function testScriptQueryFilterAndSort(): void
    {
        $activeId = $this->createScript('Test Script B ' . time(), true);
        $inactiveId = $this->createScript('Test Script A ' . time());

        // filter by active state
        $r0 = new ScriptQueryRequest($this->account->id());
        $r0->filter()->label('Test Script ')->active(true);
        $r1 = new ScriptQueryRequest($this->account->id());
        $r1->filter()->label('Test Script ')->active(false);
        // sort by name within the test scripts
        $r2 = new ScriptQueryRequest($this->account->id());
        $r2->filter()->label('Test Script ');
        $r2->sort()->label(true);

        $bundle = $this->client->perform([$r0, $r1, $r2]);
        foreach ([0, 1, 2] as $i) {
            $this->assertInstanceOf(ScriptQueryResponse::class, $bundle->response($i), $this->describe($bundle->response($i)));
        }

        $this->assertSame([$activeId], $bundle->response(0)->list());
        $this->assertSame([$inactiveId], $bundle->response(1)->list());
        $this->assertSame([$inactiveId, $activeId], $bundle->response(2)->list());
    }
}
