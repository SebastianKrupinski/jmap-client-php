<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2023 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use InvalidArgumentException;
use JmapClient\Authentication\Basic;
use JmapClient\Authentication\Bearer;
use JmapClient\Authentication\IAuthentication;
use JmapClient\Authentication\IAuthenticationCookie;
use JmapClient\Authentication\IAuthenticationCustom;
use JmapClient\Authentication\IAuthenticationJsonBasic;
use JmapClient\Authentication\JsonBasic;
use JmapClient\Authentication\None;
use JmapClient\Requests\RequestBundle;
use JmapClient\Requests\RequestClasses;
use JmapClient\Responses\ResponseBundle;
use JmapClient\Responses\ResponseClasses;
use JmapClient\Session\Account;
use JmapClient\Session\AccountCollection;
use JmapClient\Session\CapabilityCollection;
use JmapClient\Session\Session;
use JmapClient\Transport\CookieJar;
use JmapClient\Transport\Transport;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

/**
 * JMAP Client for communicating with JMAP-compliant servers
 */
class Client
{
    // Transport Modes
    public const TRANSPORT_MODE_STANDARD = 'http';
    public const TRANSPORT_MODE_SECURE = 'https';
    // Transport Mode
    protected string $_transportMode = self::TRANSPORT_MODE_SECURE . '://';
    // Transport Headers
    protected array $_transportHeaders = [
        'User-Agent' => 'PHP-JMAP-Client/1.0 (1.0; x64)',
        'Connection' => 'Keep-Alive',
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Content-Type' => 'application/json; charset=utf-8',
        'Accept' => 'application/json',
    ];
    protected bool $_transportHttpErrors = true;
    // Transport - PSR-18 client and PSR-17 factories (injected or discovered)
    protected ?ClientInterface $_transportClient;
    protected ?RequestFactoryInterface $_requestFactory;
    protected ?StreamFactoryInterface $_streamFactory;
    // Transport - request chokepoint built from the client and factories
    protected ?Transport $_transport = null;
    // Transport Cookie
    protected ?string $_transportCookieJar = null;
    protected $_transportCookieStoreRetrieve = null;
    protected $_transportCookieStoreDeposit =  null;
    // Transport Request Retention
    protected bool $_transportRequestHeaderFlag = false;
    protected bool $_transportRequestBodyFlag = false;
    // Transport Response Retention
    protected bool $_transportResponseHeaderFlag = false;
    protected bool $_transportResponseBodyFlag = false;
    // Transport Logging
    protected bool $_transportLogState = false;
    // Empty means defer to the system temp dir
    protected string $_transportLogLocation = '';
    // Transport Redirect Max
    protected int $_transportRedirectAttempts = 3;
    // Service Host
    protected string $_ServiceHost = '';
    // Service Locations
    protected string $_ServiceDiscoveryPath = '/.well-known/jmap';
    protected string $_ServiceCommandLocation = '';
    protected string $_ServiceDownloadLocation = '';
    protected string $_ServiceUploadLocation = '';
    protected string $_ServiceEventLocation = '';
    // Service Authentication
    protected IAuthentication $_ServiceAuthentication;
    // Session State
    protected bool $_SessionConnected = false;
    protected ?Session $_SessionData = null;

    public function __construct(
        string $host = '',
        ?IAuthentication $authentication = null,
        ?ClientInterface $transportClient = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
    ) {
        // store injected transport client and factories
        $this->_transportClient = $transportClient;
        $this->_requestFactory = $requestFactory;
        $this->_streamFactory = $streamFactory;
        // set service host
        if (!empty($host)) {
            $this->setHost($host);
        }
        // set service authentication
        if (isset($authentication)) {
            $this->setAuthentication($authentication);
        }
    }

    /**
     * Configures custom request class types for command or parameter request mapping
     */
    public function configureRequestTypes(string $collection, string $id, string $value)
    {
        if ($collection === 'command') {
            RequestClasses::setCommand($id, $value);
        } elseif ($collection === 'parameters') {
            RequestClasses::setParameter($id, $value);
        }
    }

    /**
     * Configures custom response class types for command or parameter response mapping
     */
    public function configureResponseTypes(string $collection, string $id, string $value)
    {
        if ($collection === 'command') {
            ResponseClasses::setCommand($id, $value);
        } elseif ($collection === 'parameters') {
            ResponseClasses::setParameter($id, $value);
        }
    }

    /**
     * Sets the PSR-18 HTTP client used for all requests
     */
    public function configureTransportClient(ClientInterface $client): void
    {
        $this->_transportClient = $client;
        $this->_transport = null;
    }

    /**
     * Sets the PSR-17 request factory
     */
    public function configureRequestFactory(RequestFactoryInterface $factory): void
    {
        $this->_requestFactory = $factory;
        $this->_transport = null;
    }

    /**
     * Sets the PSR-17 stream factory
     */
    public function configureStreamFactory(StreamFactoryInterface $factory): void
    {
        $this->_streamFactory = $factory;
        $this->_transport = null;
    }

    /**
     * Configures the transport mode (http or https)
     */
    public function configureTransportMode(string $value): void
    {
        $this->_transportMode = match ($value) {
            self::TRANSPORT_MODE_STANDARD => self::TRANSPORT_MODE_STANDARD . '://',
            self::TRANSPORT_MODE_SECURE => self::TRANSPORT_MODE_SECURE . '://',
            default => throw new InvalidArgumentException('Invalid transport mode'),
        };
    }

    /**
     * Enables or disables throwing on HTTP error status codes (>= 400)
     */
    public function configureTransportErrors(bool $value): void
    {
        $this->_transportHttpErrors = $value;
        $this->_transport?->httpErrors($value);
    }

    /**
     * Enables or disables transport logging
     */
    public function configureTransportLogState(bool $value): void
    {
        $this->_transportLogState = $value;
        $this->_transport?->logState($value);
    }

    /**
     * Configures the file path where transport logs are written
     */
    public function configureTransportLogLocation(string $value): void
    {
        $this->_transportLogLocation = $value;
        $this->_transport?->logLocation($value);
    }

    /**
     * Enables or disables retention of raw request headers sent
     */
    public function retainTransportRequestHeader(bool $value): void
    {
        $this->_transportRequestHeaderFlag = $value;
        $this->_transport?->retainRequestHeader($value);
    }

    /**
     * Enables or disables retention of raw request body sent
     */
    public function retainTransportRequestBody(bool $value): void
    {
        $this->_transportRequestBodyFlag = $value;
        $this->_transport?->retainRequestBody($value);
    }

    /**
     * Enables or disables retention of raw response headers received
     */
    public function retainTransportResponseHeader(bool $value): void
    {
        $this->_transportResponseHeaderFlag = $value;
        $this->_transport?->retainResponseHeader($value);
    }

    /**
     * Enables or disables retention of raw response body received
     */
    public function retainTransportResponseBody(bool $value): void
    {
        $this->_transportResponseBodyFlag = $value;
        $this->_transport?->retainResponseBody($value);
    }

    /**
     * Returns the last retained request headers that were sent
     */
    public function discloseTransportRequestHeader(): array
    {
        return $this->_transport?->requestHeaderData() ?? [];
    }

    /**
     * Returns the last retained request body that was sent
     */
    public function discloseTransportRequestBody(): string|null
    {
        return $this->_transport?->requestBodyData() ?? null;
    }

    /**
     * Returns the last retained HTTP response code that was received
     */
    public function discloseTransportResponseCode(): int
    {
        return $this->_transport?->responseCode() ?? 0;
    }

    /**
     * Returns the last retained response headers that were received
     */
    public function discloseTransportResponseHeader(): array
    {
        return $this->_transport?->responseHeaderData() ?? [];
    }

    /**
     * Returns the last retained response body that was received
     */
    public function discloseTransportResponseBody(): string|null
    {
        return $this->_transport?->responseBodyData() ?? null;
    }

    /**
     * Sets the User-Agent header for transport requests
     */
    public function setTransportAgent(string $value): void
    {
        $this->_transportHeaders['User-Agent'] = $value;
    }

    /**
     * Gets the current User-Agent header value
     */
    public function getTransportAgent(): string|null
    {
        return $this->_transportHeaders['User-Agent'];
    }

    /**
     * Gets the service host parameter
     */
    public function getHost(): string
    {
        return $this->_ServiceHost;
    }

    /**
     * Sets the service host parameter to be used for all requests
     */
    public function setHost(string $value): void
    {
        $this->_ServiceHost = $value;
        $this->_transport = null;
    }

    /**
     * Gets the service discovery path
     */
    public function getDiscoveryPath(): string
    {
        return $this->_ServiceDiscoveryPath;
    }

    /**
     * Sets the service discovery path parameter to be used for initial connection
     */
    public function setDiscoveryPath(string $value): void
    {
        $this->_ServiceDiscoveryPath = $value;
        $this->_transport = null;
    }

    /**
     * Gets the service discovery path
     */
    public function getCommandLocation(): string
    {
        return $this->_ServiceCommandLocation;
    }

    /**
     * Sets the service command location url
     */
    public function setCommandLocation(string $value): void
    {
        $this->_ServiceCommandLocation = $value;
    }

    /**
     * Gets the service blob/raw data download location url
     */
    public function getDownloadLocation(): string
    {
        return $this->_ServiceDownloadLocation;
    }

    /**
     * Sets the service blob/raw data download location url
     */
    public function setDownloadLocation(string $value): void
    {
        $this->_ServiceDownloadLocation = $value;
    }

    /**
     * Gets the service blob/raw data upload location url
     */
    public function getUploadLocation(): string
    {
        return $this->_ServiceUploadLocation;
    }

    /**
     * Sets the service blob/raw data upload location url
     */
    public function setUploadLocation(string $value): void
    {
        $this->_ServiceUploadLocation = $value;
    }

    /**
     * Gets the service events stream location url
     */
    public function getEventLocation(): string
    {
        return $this->_ServiceEventLocation;
    }

    /**
     * Sets the service events stream location url
     */
    public function setEventLocation(string $value): void
    {
        $this->_ServiceEventLocation = $value;
    }

    /**
     * Gets the authentication parameters object
     */
    public function getAuthentication(): IAuthentication
    {
        return $this->_ServiceAuthentication;
    }

    /**
     * Sets the authentication parameters to be used for all requests
     */
    public function setAuthentication(IAuthentication $value, bool $reset = true): void
    {
        // store parameter
        $this->_ServiceAuthentication = $value;
        // destroy existing client will need to be initialized again
        if ($reset) {
            $this->_transport = null;
        }
        // set service no authentication
        if ($this->_ServiceAuthentication instanceof None) {
            unset($this->_transportHeaders['Authorization']);
        }
        // set service json basic authentication
        if ($this->_ServiceAuthentication instanceof JsonBasic) {
            unset($this->_transportHeaders['Authorization']);
        }
        // set service basic authentication
        if ($this->_ServiceAuthentication instanceof Basic) {
            $this->_transportHeaders['Authorization'] = 'Basic ' . base64_encode($this->_ServiceAuthentication->getId() . ':' . $this->_ServiceAuthentication->getSecret());
        }
        // set service bearer authentication
        if ($this->_ServiceAuthentication instanceof Bearer) {
            $this->_transportHeaders['Authorization'] = 'Bearer ' . $this->_ServiceAuthentication->getToken();
        }
    }

    /**
     * Establishes connection with the JMAP server and retrieves session information
     */
    public function connect(): Session
    {
        // authenticate and retrieve json session
        $sessionData = $this->authenticate();
        // create session object
        $this->_SessionData = new Session($sessionData);
        // service authentication - update token if provided
        if (isset($sessionData['accessToken'])) {
            $this->setAuthentication(new Bearer('jmap-client', $sessionData['accessToken'], 0), false);
        }
        // extract required locations
        $this->_ServiceCommandLocation = $this->_SessionData->commandUrl();
        $this->_ServiceDownloadLocation = $this->_SessionData->downloadUrl();
        $this->_ServiceUploadLocation = $this->_SessionData->uploadUrl();
        $this->_ServiceEventLocation = $this->_SessionData->eventUrl();
        // session connected
        $this->_SessionConnected = true;
        // return session object
        return $this->_SessionData;
    }

    /**
     * Performs authentication with the JMAP server based on the configured authentication method
     */
    protected function authenticate(): ?array
    {
        // perform initial authentication
        if ($this->_ServiceAuthentication instanceof IAuthenticationCustom) {
            return $this->authenticateCustom();
        }

        if ($this->_ServiceAuthentication instanceof IAuthenticationJsonBasic) {
            return $this->authenticateJson();
        }

        if ($this->_ServiceAuthentication instanceof IAuthenticationCookie) {
            return $this->authenticateSession();
        }

        return $this->authenticateSimple();
    }

    /**
     * Performs simple authentication using Basic or Bearer authentication methods
     */
    protected function authenticateSimple(): ?array
    {
        $location = $this->_transportMode . $this->_ServiceHost . $this->_ServiceDiscoveryPath;

        // perform transmit and receive
        $response = $this->transceive('GET', $location, $this->_transportHeaders, null, ['allow_redirects' => true]);

        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Performs JSON-based authentication with multi-step username and password exchange
     */
    protected function authenticateJson(): ?array
    {
        if (!$this->_ServiceAuthentication instanceof IAuthenticationJsonBasic) {
            return [];
        }
        $authentication = $this->_ServiceAuthentication;
        // determine if cookie mode is enabled
        if ($authentication instanceof IAuthenticationCookie && is_callable($authentication->getCookieStoreRetrieve())) {
            $session = $this->authenticateSession();
            if ($session !== []) {
                return $session;
            }
        }

        if (!empty($authentication->getLocation())) {
            $location = $this->_transportMode . $this->_ServiceHost . $authentication->getLocation();
        } else {
            $location = $this->_transportMode . $this->_ServiceHost . $this->_ServiceDiscoveryPath;
        }

        $clientHeaders = $this->_transportHeaders;
        $clientOptions = ['allow_redirects' => true];

        if ($authentication instanceof IAuthenticationCookie) {
            $this->transport()->setCookieJar(new CookieJar());
        }

        /*===== perform initial transaction =====*/
        $requestData = json_encode((object)['type' => 'start']);
        $response = $this->transceive('POST', $location, $clientHeaders, $requestData, $clientOptions);
        $responseData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        // determine if login id was returned in initial request and fail if not
        if (!isset($responseData['loginId'])) {
            return null;
        }

        /*===== perform username exchange transaction =====*/
        $serviceLoginId = $responseData['loginId'];
        $requestData = json_encode((object)[
            'type' => 'username',
            'username' => $this->_ServiceAuthentication->getId(),
            'loginId' => $serviceLoginId,
        ]);
        $response = $this->transceive('POST', $location, $clientHeaders, $requestData, $clientOptions);
        $responseData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        // determine if login id was returned in initial request and fail if not
        if (!isset($responseData['loginId'])) {
            return null;
        }

        /*===== perform password exchange transaction =====*/
        $serviceLoginId = $responseData['loginId'];
        $requestData = json_encode((object)[
            'type' => 'password',
            'value' => $this->_ServiceAuthentication->getSecret(),
            'remember' => false,
            'loginId' => $serviceLoginId,
        ]);
        $response = $this->transceive('POST', $location, $clientHeaders, $requestData, $clientOptions);
        $responseData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if ($authentication instanceof IAuthenticationCookie && is_callable($authentication->getCookieStoreDeposit())) {
            $deposit = $authentication->getCookieStoreDeposit();
            $jar = $this->transport()->getCookieJar();
            $deposit($authentication->getCookieStoreId(), $jar !== null ? $jar->toArray() : []);
        }

        return $responseData;
    }

    /**
     * Performs cookie-based session authentication using stored cookie data
     */
    protected function authenticateSession(): ?array
    {
        // evaluate and validate authentication type
        if (!$this->_ServiceAuthentication instanceof IAuthenticationCookie) {
            return [];
        }
        if (!is_callable($this->_ServiceAuthentication->getCookieStoreRetrieve())) {
            return [];
        }
        $authentication = $this->_ServiceAuthentication;

        $cookieStore = $authentication->getCookieStoreRetrieve();
        $cookieData = $cookieStore($authentication->getCookieStoreId());
        if (empty($cookieData)) {
            return [];
        }
        $this->transport()->setCookieJar(CookieJar::fromArray($cookieData));

        if (!empty($authentication->getCookieAuthenticationLocation())) {
            $location = $this->_transportMode . $this->_ServiceHost . $authentication->getCookieAuthenticationLocation();
        } else {
            $location = $this->_transportMode . $this->_ServiceHost . $this->_ServiceDiscoveryPath;
        }

        $headers = $this->_transportHeaders;
        unset($headers['Authentication']);

        $response = $this->transceive('GET', $location, $headers, null, ['allow_redirects' => true]);
        $responseData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if ($responseData[0]) {
            return $responseData[0];
        } else {
            return [];
        }
    }

    /**
     * Performs custom authentication using a user-defined authentication callable
     */
    protected function authenticateCustom(): ?array
    {
        // evaluate and validate authentication type
        if (!$this->_ServiceAuthentication instanceof IAuthenticationCustom) {
            return [];
        }
        if (!is_callable($this->_ServiceAuthentication->authenticate())) {
            return [];
        }
        $authenticate = $this->_ServiceAuthentication->authenticate();

        return $authenticate($this->transport());
    }

    /**
     * Executes one or more JMAP commands and returns the response bundle
     */
    public function perform(RequestBundle|array $commands): ResponseBundle
    {
        // evaluate if client is connected
        if (!$this->_SessionConnected) {
            $this->connect();
        }

        // evaluate if command(s) was passed as a request bundled
        if ($commands instanceof RequestBundle) {
            $requests = $commands;
        } else {
            // construct request bundle object
            $requests  = new RequestBundle(...$commands);
        }

        // serialize request
        $data = $requests->jsonEncode();

        // transmit and receive
        $data = $this->transceive('POST', $this->_ServiceCommandLocation, $this->_transportHeaders, $data)->getBody()->getContents();

        // deserialize response
        $responses = new ResponseBundle();
        if ($data !== '') {
            $responses->jsonDecode($data);
        }

        return $responses;
    }

    /**
     * Transmits a request and returns the response.
     *
     * @param array<string, string> $headers
     * @param string|resource|StreamInterface|null $body
     * @param array{allow_redirects?: bool, sink?: resource} $options
     *
     * @throws RequestException When the response status is >= 400 and error handling is enabled.
     * @throws TransportException When the underlying client cannot deliver the request.
     */
    public function transceive(string $method, string $uri, array $headers = [], $body = null, array $options = []): ResponseInterface
    {
        return $this->transport()->transceive($method, $uri, $headers, $body, $options);
    }

    private function transport(): Transport
    {
        if ($this->_transport === null) {
            $transport = new Transport(
                $this->_transportClient ?? Psr18ClientDiscovery::find(),
                $this->_requestFactory ?? Psr17FactoryDiscovery::findRequestFactory(),
                $this->_streamFactory ?? Psr17FactoryDiscovery::findStreamFactory(),
            );
            $transport->httpErrors($this->_transportHttpErrors);
            $transport->maxRedirects($this->_transportRedirectAttempts);
            $transport->retainRequestHeader($this->_transportRequestHeaderFlag);
            $transport->retainRequestBody($this->_transportRequestBodyFlag);
            $transport->retainResponseHeader($this->_transportResponseHeaderFlag);
            $transport->retainResponseBody($this->_transportResponseBodyFlag);
            $transport->logState($this->_transportLogState);
            if ($this->_transportLogLocation !== '') {
                $transport->logLocation($this->_transportLogLocation);
            }
            $this->_transport = $transport;
        }

        return $this->_transport;
    }

    /**
     * Downloads a blob/file from the JMAP server to a variable or stream resource
     */
    public function download(string $account, string $identifier, &$data, string $type = 'application/octet-stream', string $name = 'file.bin'): void
    {
        // evaluate if client is connected
        if (!$this->_SessionConnected) {
            $this->connect();
        }

        // validate data
        if (is_resource($data) && get_resource_type($data) !== 'stream') {
            throw new InvalidArgumentException('Invalid resource passed');
        }

        // replace command options
        $location = $this->_ServiceDownloadLocation;
        $location = str_replace('{accountId}', $account, $location);
        $location = str_replace('{blobId}', $identifier, $location);
        $location = str_replace('{type}', $type, $location);
        $location = str_replace('{name}', $name, $location);

        // modify specific headers
        $transportHeaders = $this->_transportHeaders;
        unset(
            $transportHeaders['Content-Type'],
            $transportHeaders['Accept']
        );

        // determine data destination and set receiving options
        if (is_resource($data)) {
            // execute request and write to resource stream
            $this->transceive('GET', $location, $transportHeaders, null, ['sink' => $data]);
        } else {
            // execute request and capture response body
            $data = $this->transceive('GET', $location, $transportHeaders)->getBody()->getContents();
        }
    }

    /**
     * Opens a streamed blob/file download from the JMAP server.
     */
    public function downloadStream(string $account, string $identifier, string $type = 'application/octet-stream', string $name = 'file.bin'): StreamInterface
    {
        // evaluate if client is connected
        if (!$this->_SessionConnected) {
            $this->connect();
        }

        // replace command options
        $location = $this->_ServiceDownloadLocation;
        $location = str_replace('{accountId}', $account, $location);
        $location = str_replace('{blobId}', $identifier, $location);
        $location = str_replace('{type}', $type, $location);
        $location = str_replace('{name}', $name, $location);

        // modify specific headers
        $transportHeaders = $this->_transportHeaders;
        unset(
            $transportHeaders['Content-Type'],
            $transportHeaders['Accept']
        );

        return $this->transceive('GET', $location, $transportHeaders, null, ['stream' => true])->getBody();
    }

    /**
     * Uploads a blob/file to the JMAP server from a variable or stream resource
     */
    public function upload(string $account, string $type, &$data): string
    {
        // evaluate if client is connected
        if (!$this->_SessionConnected) {
            $this->connect();
        }

        // validate data
        if (is_resource($data) && get_resource_type($data) !== 'stream') {
            throw new InvalidArgumentException('Invalid resource passed');
        }

        // replace command options
        $location = str_replace('{accountId}', $account, $this->_ServiceUploadLocation);

        // modify specific headers
        $transportHeaders = $this->_transportHeaders;
        $transportHeaders['Content-Type'] = $type;

        // execute request
        return $this->transceive('POST', $location, $transportHeaders, $data)->getBody()->getContents();
    }

    /**
     * Returns the current session connection status
     */
    public function sessionStatus(): bool
    {
        return $this->_SessionConnected;
    }

    /**
     * Returns the current session data object
     */
    public function sessionData(): ?Session
    {
        return $this->_SessionData;
    }

    /**
     * Checks if the session supports a specific capability
     */
    public function sessionCapable(string $value, bool $standard = true): bool
    {
        if (!$this->_SessionData) {
            return false;
        }
        if ($standard === false) {
            return $this->_SessionData->capable($value);
        } else {
            return $this->_SessionData->capable('urn:ietf:params:jmap:' . $value);
        }
    }

    /**
     * Retrieves session capabilities, either all or for a specific capability
     */
    public function sessionCapabilities(?string $value = null, bool $standard = true): CapabilityCollection|null
    {
        if (!$this->_SessionData) {
            return null;
        }
        if ($standard === true && !empty($value)) {
            return $this->_SessionData->capability('urn:ietf:params:jmap:' . $value);
        } elseif ($standard === false && !empty($value)) {
            return $this->_SessionData->capability($value);
        } else {
            return $this->_SessionData->capabilities();
        }
    }

    /**
     * Returns all session accounts as a collection
     */
    public function sessionAccounts(): AccountCollection|null
    {
        if (!$this->_SessionData) {
            return null;
        }
        return $this->_SessionData->accounts();
    }

    /**
     * Returns the primary/default account for a specific capability or the core account
     */
    public function sessionAccountDefault(?string $value = null, bool $standard = true): Account|null
    {
        if (!$this->_SessionData) {
            return null;
        }
        if ($standard === true && !empty($value)) {
            return $this->_SessionData->primaryAccount('urn:ietf:params:jmap:' . $value);
        } elseif ($standard === false && !empty($value)) {
            return $this->_SessionData->primaryAccount($value);
        } else {
            return $this->_SessionData->primaryAccount('urn:ietf:params:jmap:core');
        }
    }
}
