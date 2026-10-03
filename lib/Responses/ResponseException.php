<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses;

class ResponseException
{
    public const RESPONSE_OPERATION = 0;
    public const RESPONSE_OBJECT = 1;
    public const RESPONSE_IDENTIFIER = 2;

    // method level errors applicable to any method (RFC 8620 section 3.6.2)

    /**
     * Some internal server resource was temporarily unavailable, the call may be retried later
     */
    public const SERVER_UNAVAILABLE = 'serverUnavailable';

    /**
     * An unexpected or unknown error occurred during the processing of the call, the state was not changed
     */
    public const SERVER_FAIL = 'serverFail';

    /**
     * Some, but not all, expected changes described by the method occurred
     */
    public const SERVER_PARTIAL_FAIL = 'serverPartialFail';

    /**
     * The server does not recognise the method name
     */
    public const UNKNOWN_METHOD = 'unknownMethod';

    /**
     * One of the arguments is of the wrong type or otherwise invalid, or a required argument is missing
     */
    public const INVALID_ARGUMENTS = 'invalidArguments';

    /**
     * The method used a result reference for one of its arguments, but this failed to resolve
     */
    public const INVALID_RESULT_REFERENCE = 'invalidResultReference';

    /**
     * The method and arguments are valid, but executing the method would violate an access control list or other permissions policy
     */
    public const FORBIDDEN = 'forbidden';

    /**
     * The accountId does not correspond to a valid account
     */
    public const ACCOUNT_NOT_FOUND = 'accountNotFound';

    /**
     * The accountId given corresponds to a valid account, but the account does not support this method or data type
     */
    public const ACCOUNT_NOT_SUPPORTED_BY_METHOD = 'accountNotSupportedByMethod';

    /**
     * This method modifies state, but the account is read-only
     */
    public const ACCOUNT_READ_ONLY = 'accountReadOnly';

    // method specific errors (RFC 8620 sections 5.1 to 5.6)

    /**
     * The number of ids in a /get call, or of objects in a /set call, exceeds the
     * server's maxObjectsInGet or maxObjectsInSet limit (RFC 8620 sections 5.1 and 5.3)
     */
    public const REQUEST_TOO_LARGE = 'requestTooLarge';

    /**
     * The server cannot calculate the changes from the given state, the client must invalidate its cache (RFC 8620 sections 5.2 and 5.6)
     */
    public const CANNOT_CALCULATE_CHANGES = 'cannotCalculateChanges';

    /**
     * An ifInState argument was supplied and it does not match the current state (RFC 8620 sections 5.3 and 5.4)
     */
    public const STATE_MISMATCH = 'stateMismatch';

    /**
     * The fromAccountId of a /copy call does not correspond to a valid account (RFC 8620 section 5.4)
     */
    public const FROM_ACCOUNT_NOT_FOUND = 'fromAccountNotFound';

    /**
     * The fromAccountId of a /copy call corresponds to an account that does not support this data type (RFC 8620 section 5.4)
     */
    public const FROM_ACCOUNT_NOT_SUPPORTED_BY_METHOD = 'fromAccountNotSupportedByMethod';

    /**
     * An anchor argument was supplied, but it cannot be found in the query results (RFC 8620 section 5.5)
     */
    public const ANCHOR_NOT_FOUND = 'anchorNotFound';

    /**
     * The sort is syntactically valid, but includes a property or collation the server does not support (RFC 8620 section 5.5)
     */
    public const UNSUPPORTED_SORT = 'unsupportedSort';

    /**
     * The filter is syntactically valid, but the server cannot process it (RFC 8620 section 5.5)
     */
    public const UNSUPPORTED_FILTER = 'unsupportedFilter';

    /**
     * The number of changes exceeds the maxChanges argument of a /queryChanges call (RFC 8620 section 5.6)
     */
    public const TOO_MANY_CHANGES = 'tooManyChanges';

    protected array $_response = [];

    public function __construct(array $response = [])
    {
        $this->_response = $response;
    }

    public function identifier(): string
    {
        return isset($this->_response[self::RESPONSE_IDENTIFIER]) ? $this->_response[self::RESPONSE_IDENTIFIER] : '';
    }

    public function type(): string
    {
        return isset($this->_response[self::RESPONSE_OBJECT]['type']) ? $this->_response[self::RESPONSE_OBJECT]['type'] : '';
    }

    public function description(): string
    {
        return isset($this->_response[self::RESPONSE_OBJECT]['description']) ? $this->_response[self::RESPONSE_OBJECT]['description'] : '';
    }
}
