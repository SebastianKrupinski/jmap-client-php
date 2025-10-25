# JMAP PHP Client

A fully-typed JMAP (JSON Meta Application Protocol) client library for PHP.

## About

This project provides a type-safe, object-oriented PHP client library for interacting with JMAP servers. JMAP is a modern, JSON-based protocol for accessing email, calendar, and contact data, designed to be more efficient and flexible than traditional protocols like IMAP and CalDAV.

The library is built with PHP 8.0+ strict typing in mind, ensuring robust type safety and excellent IDE support throughout your codebase.

## Features

- **Full JMAP Protocol Support** - Complete implementation of the JMAP specification (Still in progress)
- **Type-Safe** - Built with PHP 8.0+ strict types for better code quality and IDE support
- **Multiple Authentication Methods** - Support for Basic Auth, Bearer Token, JSON-RPC, and custom authentication schemes
- **Session Management** - Automatic session handling and capability negotiation
- **Request/Response Handling** - Robust request bundling and response parsing
- **Cookie Support** - Built-in cookie jar management for stateful sessions
- **Error Handling** - Comprehensive exception handling

## Requirements

- **PHP** >= 8.0
- **ext-json** - JSON extension for PHP
- **Guzzle HTTP Client** >= 7.0

## Installation

Install the library using Composer:

```bash
composer require sebastiankrupinski/jmap-client-php
```

Or, if you're manually managing dependencies in your `composer.json`:

```json
{
    "require": {
        "sebastiankrupinski/jmap-client-php": "^1.0",
        "guzzlehttp/guzzle": "^7.0"
    }
}
```

Then run:

```bash
composer install
```

## Quick Start

```php
<?php

use JmapClient\Client;
use JmapClient\Authentication\Basic;

// Create a new client
$client = new Client();

// Configure transport
$client->configureTransportMode('https://');
$client->setHost('jmap.example.com:443');
$client->configureTransportVerification(true);

// Set up authentication
$auth = new Basic('user@example.com', 'password');
$client->setAuthentication($auth);

// Connect to the JMAP server
$session = $client->connect();
echo "Connected to: " . $session->apiUrl();

// Access the default account for core operations
$account = $client->sessionAccountDefault(null, true);
if ($account !== null) {
    echo "Account ID: " . $account->id();
}
```

## Usage Examples

### Setting Up a Client with Different Authentication Methods

#### Basic Authentication

```php
use JmapClient\Client;
use JmapClient\Authentication\Basic;

$client = new Client();
$client->configureTransportMode('https://');
$client->setHost('jmap.example.com:443');
$client->configureTransportVerification(true);

// Basic authentication with username and password
$auth = new Basic('user@example.com', 'password');
$client->setAuthentication($auth);

// Connect to establish the session
$session = $client->connect();
```

#### Bearer Token Authentication

```php
use JmapClient\Client;
use JmapClient\Authentication\Bearer;

$client = new Client();
$client->configureTransportMode('https://');
$client->setHost('jmap.example.com:443');
$client->configureTransportVerification(true);

// Bearer token authentication
$auth = new Bearer('user@example.com', 'your-access-token', 1735689600);
$client->setAuthentication($auth);

$session = $client->connect();
```

### Working with Contacts

#### Fetching Address Books

```php
use JmapClient\Requests\Contacts\AddressBookGet;

$client = // ... (setup client from examples above)

// Get the default account for contacts
$account = $client->sessionAccountDefault('contacts', true);
if ($account === null) {
    echo "No contacts account found";
    exit;
}

$accountId = $account->id();

// Create a request to fetch all address books
$request = new AddressBookGet($accountId);

// Execute the request
$response = $client->perform([$request]);

// Get the response for the first (and only) request
$addressBookResponse = $response->response(0);

// Process the results
foreach ($addressBookResponse->objects() as $addressBook) {
    echo "Address Book: " . $addressBook->label() . " (ID: " . $addressBook->id() . ")\n";
}
```

#### Fetching Specific Contacts

```php
use JmapClient\Requests\Contacts\ContactGet;

// Fetch specific contacts by ID
$request = new ContactGet($accountId);
$request->target('contact-id-1', 'contact-id-2', 'contact-id-3');

// You can also limit properties to fetch
$request->property('id', 'uid', 'name', 'emails');

$response = $client->perform([$request]);
$contactResponse = $response->response(0);

// Access contact properties
foreach ($contactResponse->objects() as $contact) {
    if ($contact->name() !== null) {
        echo "Name: " . json_encode($contact->name()->components()) . "\n";
    }
    
    if ($contact->emails() !== null) {
        foreach ($contact->emails() as $email) {
            echo "  Email: " . $email->email() . " (" . ($email->type() ?? 'personal') . ")\n";
        }
    }
}
```

#### Listing All Contacts in an Address Book

```php
use JmapClient\Requests\Contacts\ContactQuery;
use JmapClient\Requests\Contacts\ContactGet;

$account = $client->sessionAccountDefault('contacts', true);
$accountId = $account->id();

// First, query for all contacts in a specific address book
$queryRequest = new ContactQuery($accountId);
// Set up filter for the address book
$queryRequest->filter()->in('address-book-id');

// Then create a request to fetch the contact details
// using the IDs from the query result
$getRequest = new ContactGet($accountId);
$getRequest->targetFromRequest($queryRequest, '/ids');

// Perform both requests in a single batch
$response = $client->perform([$queryRequest, $getRequest]);

// Get the response to the GET request (second request, index 1)
$contactResponse = $response->response(1);

// Process all contacts
foreach ($contactResponse->objects() as $contact) {
    echo $contact->id() . ": " . json_encode($contact->name()) . "\n";
}
```

### Server Capability Detection

```php
// Check if server supports specific JMAP extensions
$supportsContacts = $client->sessionCapable('contacts', true);
$supportsMail = $client->sessionCapable('mail', true);
$supportsTasks = $client->sessionCapable('tasks', true);

// Get all available capabilities
$capabilities = $client->sessionCapabilities(null, true);
if ($capabilities !== null) {
    foreach ($capabilities->objects() as $capability => $data) {
        echo "Capability: " . $capability . "\n";
    }
}
```

### Session Information

```php
// Get session status
if ($client->sessionStatus()) {
    echo "Client is connected\n";
}

// Get session data
$session = $client->sessionData();
if ($session !== null) {
    echo "API URL: " . $session->apiUrl() . "\n";
    echo "Download URL: " . $session->downloadUrl() . "\n";
    echo "Upload URL: " . $session->uploadUrl() . "\n";
}

// Get all available accounts
$accounts = $client->sessionAccounts();
if ($accounts !== null) {
    foreach ($accounts->objects() as $account) {
        echo "Account: " . $account->id() . " (" . $account->name() . ")\n";
    }
}
```

## Configuration

### Transport Settings

```php
$client = new Client();

// Set transport mode (http or https)
$client->configureTransportMode('https://');

// Configure host and port
$client->setHost('jmap.example.com:443');

// Set custom discovery path (if server uses non-standard JMAP endpoint)
$client->setDiscoveryPath('/.well-known/jmap');

// Enable/disable SSL verification
$client->configureTransportVerification(true);

// Set custom user agent
$client->setTransportAgent('MyApplication/1.0');

// Configure transport version (HTTP version)
$client->configureTransportVersion(2);
```

### Debug Logging

```php
// Enable transport logging to file for debugging
$client->configureTransportLogState(true);
$client->configureTransportLogLocation('/var/log/jmap-client.log');

// This logs all HTTP requests and responses for debugging

// You can also retain specific transport data
$client->retainTransportRequestHeader(true);
$client->retainTransportRequestBody(true);
$client->retainTransportResponseHeader(true);
$client->retainTransportResponseBody(true);

// Retrieve the logged data
$requestHeaders = $client->discloseTransportRequestHeader();
$requestBody = $client->discloseTransportRequestBody();
$responseCode = $client->discloseTransportResponseCode();
$responseHeaders = $client->discloseTransportResponseHeader();
$responseBody = $client->discloseTransportResponseBody();
```

### SSL/TLS Certificate Verification

```php
// Enable certificate verification (recommended for production)
$client->configureTransportVerification(true);

// Disable verification (NOT recommended - use only for testing with self-signed certificates)
$client->configureTransportVerification(false);
```

## Error Handling

The JMAP client returns `ResponseException` objects when JMAP-level errors occur. Here's how to handle them:

```php
use JmapClient\Requests\Contacts\AddressBookGet;
use JmapClient\Responses\ResponseException;

try {
    $client = // ... (setup client)
    
    $account = $client->sessionAccountDefault('contacts', true);
    if ($account === null) {
        echo "No contacts account available";
        exit;
    }
    
    $request = new AddressBookGet($account->id());
    $response = $client->perform([$request]);
    
    // Get the response
    $result = $response->response(0);
    
    // Check if the response is an error
    if ($result instanceof ResponseException) {
        $errorType = $result->type(); // e.g., 'unknownMethod', 'invalidArguments'
        $errorMessage = $result->description();
        
        echo "JMAP Error: $errorType\n";
        echo "Message: $errorMessage\n";
        
        // Handle specific error types
        switch ($errorType) {
            case 'unknownMethod':
                echo "The requested method is not supported by the server\n";
                break;
            case 'invalidArguments':
                echo "Invalid arguments were provided to the request\n";
                break;
            case 'requestTooLarge':
                echo "Request payload exceeds server limits\n";
                break;
            case 'stateMismatch':
                echo "The state parameter doesn't match - you may need to re-sync\n";
                break;
        }
    } else {
        // Process successful response
        foreach ($result->objects() as $addressBook) {
            echo "Found: " . $addressBook->label() . "\n";
        }
    }
} catch (Exception $e) {
    // Handle connection or transport errors
    echo "Connection error: " . $e->getMessage() . "\n";
}
```

### Common JMAP Error Types

- **unknownMethod** - The JMAP method is not supported by the server
- **invalidArguments** - The request contains invalid parameters  
- **requestTooLarge** - The request payload exceeds server size limits
- **unsupportedFilter** - The filter criteria is not supported
- **stateMismatch** - The state parameter doesn't match the current server state
- **forbidden** - The authenticated user does not have permission
- **accountNotFound** - The specified account was not found
- **serverUnavailable** - The server is temporarily unavailable

## Contributing

Contributions are welcome! Please ensure that:

1. Code is properly type-hinted
2. Changes include appropriate documentation

## License

This project is licensed under the GNU Affero General Public License v3.0 (AGPL-3.0-or-later). See the [LICENSE](LICENSE) file for details.

## Author

**Sebastian Krupinski**
- Email: krupinski01@gmail.com
- GitHub: [SebastianKrupinski](https://github.com/SebastianKrupinski/)

## Support

For issues, questions, or contributions, please visit the [project repository](https://github.com/SebastianKrupinski/jmap-client-php).

---

**Note:** This library implements the JMAP specification as defined at https://jmap.io/
