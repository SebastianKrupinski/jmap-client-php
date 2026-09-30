<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Session;

use JmapClient\Session\Capability;
use JmapClient\Session\CoreCapability;
use JmapClient\Session\Session;
use PHPUnit\Framework\TestCase;

class CoreCapabilityTest extends TestCase
{
    public function testSessionExposesCoreLimits(): void
    {
        $session = new Session([
            'capabilities' => [
                'urn:ietf:params:jmap:core' => [
                    'maxSizeUpload' => 50000000,
                    'maxConcurrentUpload' => 4,
                    'maxSizeRequest' => 10000000,
                    'maxConcurrentRequests' => 4,
                    'maxCallsInRequest' => 16,
                    'maxObjectsInGet' => 500,
                    'maxObjectsInSet' => 500,
                    'collationAlgorithms' => ['i;ascii-numeric', 'i;ascii-casemap', 'i;unicode-casemap'],
                ],
            ],
        ]);

        $core = $session->coreCapability();

        $this->assertInstanceOf(CoreCapability::class, $core);
        $this->assertSame(50000000, $core->maxSizeUpload());
        $this->assertSame(4, $core->maxConcurrentUpload());
        $this->assertSame(10000000, $core->maxSizeRequest());
        $this->assertSame(4, $core->maxConcurrentRequests());
        $this->assertSame(16, $core->maxCallsInRequest());
        $this->assertSame(500, $core->maxObjectsInGet());
        $this->assertSame(500, $core->maxObjectsInSet());
        $this->assertSame(['i;ascii-numeric', 'i;ascii-casemap', 'i;unicode-casemap'], $core->collationAlgorithms());
    }

    public function testSessionWithoutCoreCapability(): void
    {
        $session = new Session(['capabilities' => ['urn:ietf:params:jmap:mail' => []]]);

        $this->assertNull($session->coreCapability());
    }

    public function testOmittedOrInvalidPropertiesAreNull(): void
    {
        $core = new CoreCapability(new Capability(CoreCapability::ID, (object)['maxObjectsInGet' => '500']));

        $this->assertNull($core->maxObjectsInGet());
        $this->assertNull($core->maxObjectsInSet());
        $this->assertSame([], $core->collationAlgorithms());
    }
}
