<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Tests\Integration\Mail;

use JmapClient\Client;
use JmapClient\Requests\Mail\MailVacationGet as MailVacationGetRequest;
use JmapClient\Requests\Mail\MailVacationSet as MailVacationSetRequest;
use JmapClient\Responses\Mail\MailVacationGet as MailVacationGetResponse;
use JmapClient\Responses\Mail\MailVacationParameters;
use JmapClient\Responses\Mail\MailVacationSet as MailVacationSetResponse;
use JmapClient\Session\Account;
use JmapClient\Tests\Integration\ClientFactory;
use PHPUnit\Framework\TestCase;

class MailVacationTest extends TestCase
{
    private Client $client;
    private Account $account;

    /**
     * Saved original vacation state to restore after each test.
     */
    private array $originalState = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->client = ClientFactory::instance('service1');
        $this->client->connect();
        $this->account = $this->client->sessionAccountDefault('mail');

        // capture the current vacation state so we can restore it in tearDown
        $r0 = new MailVacationGetRequest($this->account->id());
        $bundle = $this->client->perform([$r0]);
        $response = $bundle->first();
        if ($response instanceof MailVacationGetResponse) {
            $vacation = $response->object(0);
            if ($vacation instanceof MailVacationParameters) {
                $this->originalState = [
                    'isEnabled' => $vacation->enabled(),
                    'subject'   => $vacation->subject(),
                    'textBody'  => $vacation->bodyText(),
                    'htmlBody'  => $vacation->bodyHtml(),
                ];
            }
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();
        // restore original vacation response state
        if (!empty($this->originalState)) {
            $r0 = new MailVacationSetRequest($this->account->id());
            $p0 = $r0->update('singleton');
            $p0->enabled($this->originalState['isEnabled'] ?? false);
            if ($this->originalState['subject'] !== null) {
                $p0->subject($this->originalState['subject']);
            }
            if ($this->originalState['textBody'] !== null) {
                $p0->textBody($this->originalState['textBody']);
            }
            if ($this->originalState['htmlBody'] !== null) {
                $p0->htmlBody($this->originalState['htmlBody']);
            }
            $this->client->perform([$r0]);
        }
    }

    public function testMailVacationGetAll(): void
    {
        // construct fetch request (no ids = retrieve all / the singleton)
        $r0 = new MailVacationGetRequest($this->account->id());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify fetch response — servers MUST always return the singleton
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailVacationGetResponse::class, $bundle->first());
        $results = $bundle->first()->objects();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        foreach ($results as $result) {
            $this->assertInstanceOf(MailVacationParameters::class, $result);
        }
    }

    public function testMailVacationGetSpecific(): void
    {
        // construct fetch request targeting the well-known singleton id
        $r0 = new MailVacationGetRequest($this->account->id());
        $r0->target('singleton');

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailVacationGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(MailVacationParameters::class, $result);
        $this->assertEquals('singleton', $result->id());
    }

    public function testMailVacationGetWithProperties(): void
    {
        // construct fetch request with limited property set
        $r0 = new MailVacationGetRequest($this->account->id());
        $r0->property('id', 'isEnabled');

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify fetch response — unrequested properties should be absent/null
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailVacationGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(MailVacationParameters::class, $result);
        $this->assertNotEmpty($result->id());
        $this->assertNull($result->subject());
        $this->assertNull($result->bodyText());
        $this->assertNull($result->bodyHtml());
    }

    public function testMailVacationSetEnable(): void
    {
        // construct update request to enable the vacation response
        $r0 = new MailVacationSetRequest($this->account->id());
        $p0 = $r0->update('singleton');
        $p0->enabled(true);
        $p0->subject('Out of office');
        $p0->textBody('I am currently out of the office.');

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify set response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailVacationSetResponse::class, $bundle->first());
        $result = $bundle->first()->updateSuccess('singleton');
        $this->assertNotNull($result);

        // fetch and confirm persisted state
        $r1 = new MailVacationGetRequest($this->account->id());
        $r1->target('singleton');
        $bundle = $this->client->perform([$r1]);
        $vacation = $bundle->first()->object(0);
        $this->assertInstanceOf(MailVacationParameters::class, $vacation);
        $this->assertTrue($vacation->enabled());
        $this->assertEquals('Out of office', $vacation->subject());
        $this->assertEquals('I am currently out of the office.', $vacation->bodyText());
    }

    public function testMailVacationSetDisable(): void
    {
        // first enable so we have a known starting state
        $r0 = new MailVacationSetRequest($this->account->id());
        $p0 = $r0->update('singleton');
        $p0->enabled(true);
        $p0->subject('Out of office');
        $p0->textBody('Away.');
        $this->client->perform([$r0]);

        // now disable
        $r1 = new MailVacationSetRequest($this->account->id());
        $p1 = $r1->update('singleton');
        $p1->enabled(false);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify set response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailVacationSetResponse::class, $bundle->first());
        $result = $bundle->first()->updateSuccess('singleton');
        $this->assertNotNull($result);

        // fetch and confirm disabled state
        $r2 = new MailVacationGetRequest($this->account->id());
        $r2->target('singleton');
        $bundle = $this->client->perform([$r2]);
        $vacation = $bundle->first()->object(0);
        $this->assertInstanceOf(MailVacationParameters::class, $vacation);
        $this->assertFalse($vacation->enabled());
    }

    public function testMailVacationSetUpdateSubject(): void
    {
        $newSubject = 'Updated OOO ' . time();

        // construct update request changing only the subject
        $r0 = new MailVacationSetRequest($this->account->id());
        $p0 = $r0->update('singleton');
        $p0->subject($newSubject);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify set response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailVacationSetResponse::class, $bundle->first());
        $result = $bundle->first()->updateSuccess('singleton');
        $this->assertNotNull($result);

        // fetch and verify subject was persisted
        $r1 = new MailVacationGetRequest($this->account->id());
        $r1->target('singleton');
        $bundle = $this->client->perform([$r1]);
        $vacation = $bundle->first()->object(0);
        $this->assertInstanceOf(MailVacationParameters::class, $vacation);
        $this->assertEquals($newSubject, $vacation->subject());
    }

    public function testMailVacationSetUpdateTextBody(): void
    {
        $newBody = 'Plain text body updated at ' . time();

        // construct update request changing only the text body
        $r0 = new MailVacationSetRequest($this->account->id());
        $p0 = $r0->update('singleton');
        $p0->textBody($newBody);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify set response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailVacationSetResponse::class, $bundle->first());
        $result = $bundle->first()->updateSuccess('singleton');
        $this->assertNotNull($result);

        // fetch and verify text body was persisted
        $r1 = new MailVacationGetRequest($this->account->id());
        $r1->target('singleton');
        $bundle = $this->client->perform([$r1]);
        $vacation = $bundle->first()->object(0);
        $this->assertInstanceOf(MailVacationParameters::class, $vacation);
        $this->assertEquals($newBody, $vacation->bodyText());
    }

    public function testMailVacationSetUpdateHtmlBody(): void
    {
        $newHtml = '<p>HTML body updated at ' . time() . '</p>';

        // construct update request changing only the HTML body
        $r0 = new MailVacationSetRequest($this->account->id());
        $p0 = $r0->update('singleton');
        $p0->htmlBody($newHtml);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify set response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailVacationSetResponse::class, $bundle->first());
        $result = $bundle->first()->updateSuccess('singleton');
        $this->assertNotNull($result);

        // fetch and verify HTML body was persisted
        $r1 = new MailVacationGetRequest($this->account->id());
        $r1->target('singleton');
        $bundle = $this->client->perform([$r1]);
        $vacation = $bundle->first()->object(0);
        $this->assertInstanceOf(MailVacationParameters::class, $vacation);
        $this->assertEquals($newHtml, $vacation->bodyHtml());
    }
}
