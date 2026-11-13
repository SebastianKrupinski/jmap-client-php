<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Sieve;

use JmapClient\Responses\ResponseBundle;
use JmapClient\Responses\Sieve\ScriptGet;
use JmapClient\Responses\Sieve\ScriptParameters;
use JmapClient\Responses\Sieve\ScriptValidate;
use PHPUnit\Framework\TestCase;

class ScriptResponseTest extends TestCase
{
    private function bundle(string $method, array $arguments): ResponseBundle
    {
        return new ResponseBundle([
            'methodResponses' => [[$method, $arguments, 'call-1']],
            'sessionState' => 'session-1',
        ]);
    }

    public function testGetDeserializesScripts(): void
    {
        $response = $this->bundle('SieveScript/get', [
            'accountId' => 'account-1',
            'state' => 'state-1',
            'list' => [
                ['id' => 'script-1', 'name' => 'Filters', 'blobId' => 'blob-1', 'isActive' => true],
                ['id' => 'script-2', 'name' => null, 'blobId' => 'blob-2', 'isActive' => false],
            ],
            'notFound' => [],
        ])->first();

        $this->assertInstanceOf(ScriptGet::class, $response);
        $script = $response->object(0);
        $this->assertInstanceOf(ScriptParameters::class, $script);
        $this->assertSame('script-1', $script->id());
        $this->assertSame('Filters', $script->label());
        $this->assertSame('blob-1', $script->blob());
        $this->assertTrue($script->active());
        $this->assertNull($response->object(1)->label());
        $this->assertFalse($response->object(1)->active());
    }

    public function testValidateWithoutError(): void
    {
        $response = $this->bundle('SieveScript/validate', ['accountId' => 'account-1', 'error' => null])->first();

        $this->assertInstanceOf(ScriptValidate::class, $response);
        $this->assertTrue($response->valid());
        $this->assertNull($response->error());
        $this->assertNull($response->errorType());
    }

    public function testValidateWithError(): void
    {
        $response = $this->bundle('SieveScript/validate', [
            'accountId' => 'account-1',
            'error' => ['type' => 'invalidScript', 'description' => 'Unknown command "foo" at line 1'],
        ])->first();

        $this->assertFalse($response->valid());
        $this->assertSame('invalidScript', $response->errorType());
        $this->assertSame('Unknown command "foo" at line 1', $response->errorDescription());
    }
}
