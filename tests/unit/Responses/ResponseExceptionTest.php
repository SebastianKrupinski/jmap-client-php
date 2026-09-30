<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses;

use JmapClient\Responses\ResponseBundle;
use JmapClient\Responses\ResponseException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ResponseExceptionTest extends TestCase
{
    public function testDeserializesRequestTooLargeError(): void
    {
        $bundle = new ResponseBundle([
            'methodResponses' => [[
                'error',
                [
                    'type' => 'requestTooLarge',
                    'description' => 'The number of ids requested by the client exceeds the maximum number the server is willing to process in a single method call.',
                ],
                'get-1',
            ]],
            'sessionState' => 'session-1',
        ]);

        $response = $bundle->first();

        $this->assertInstanceOf(ResponseException::class, $response);
        $this->assertSame(ResponseException::REQUEST_TOO_LARGE, $response->type());
        $this->assertSame('get-1', $response->identifier());
        $this->assertStringContainsString('exceeds the maximum number', $response->description());
    }

    public static function rfcErrorTypes(): array
    {
        return [
            [ResponseException::SERVER_UNAVAILABLE, 'serverUnavailable'],
            [ResponseException::SERVER_FAIL, 'serverFail'],
            [ResponseException::SERVER_PARTIAL_FAIL, 'serverPartialFail'],
            [ResponseException::UNKNOWN_METHOD, 'unknownMethod'],
            [ResponseException::INVALID_ARGUMENTS, 'invalidArguments'],
            [ResponseException::INVALID_RESULT_REFERENCE, 'invalidResultReference'],
            [ResponseException::FORBIDDEN, 'forbidden'],
            [ResponseException::ACCOUNT_NOT_FOUND, 'accountNotFound'],
            [ResponseException::ACCOUNT_NOT_SUPPORTED_BY_METHOD, 'accountNotSupportedByMethod'],
            [ResponseException::ACCOUNT_READ_ONLY, 'accountReadOnly'],
            [ResponseException::REQUEST_TOO_LARGE, 'requestTooLarge'],
            [ResponseException::CANNOT_CALCULATE_CHANGES, 'cannotCalculateChanges'],
            [ResponseException::STATE_MISMATCH, 'stateMismatch'],
            [ResponseException::FROM_ACCOUNT_NOT_FOUND, 'fromAccountNotFound'],
            [ResponseException::FROM_ACCOUNT_NOT_SUPPORTED_BY_METHOD, 'fromAccountNotSupportedByMethod'],
            [ResponseException::ANCHOR_NOT_FOUND, 'anchorNotFound'],
            [ResponseException::UNSUPPORTED_SORT, 'unsupportedSort'],
            [ResponseException::UNSUPPORTED_FILTER, 'unsupportedFilter'],
            [ResponseException::TOO_MANY_CHANGES, 'tooManyChanges'],
        ];
    }

    #[DataProvider('rfcErrorTypes')]
    public function testErrorTypeMatchesRfcName(string $constant, string $expected): void
    {
        $response = new ResponseException(['error', ['type' => $expected], 'call-1']);

        $this->assertSame($expected, $constant);
        $this->assertSame($constant, $response->type());
    }
}
