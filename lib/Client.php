<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2023 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use JmapClient\Authentication\IAuthentication;
use JmapClient\Authentication\Basic;
use JmapClient\Authentication\Bearer;
use JmapClient\Authentication\JsonBasic;
use JmapClient\Authentication\IAuthenticationCookie;
use JmapClient\Authentication\IAuthenticationCustom;
use JmapClient\Authentication\IAuthenticationJsonBasic;
use JmapClient\Authentication\None;
use JmapClient\Requests\RequestBundle;
use JmapClient\Responses\ResponseBundle;
use JmapClient\Responses\ResponseClasses;
use JmapClient\Session\Account;
use JmapClient\Session\AccountCollection;
use JmapClient\Session\CapabilityCollection;
use JmapClient\Session\Session;

/**
 * JMAP Client for communicating with JMAP-compliant servers
 */
class Client {
    // Transport Versions
    const TRANSPORT_VERSION_1 = "1.0";
    const TRANSPORT_VERSION_1_1 = "1.1";
    const TRANSPORT_VERSION_2 = "2.0";
    // Transport Modes
    const TRANSPORT_MODE_STANDARD = 'http://';
    const TRANSPORT_MODE_SECURE = 'https://';
    // Transport Mode
    protected string $_transportMode = self::TRANSPORT_MODE_SECURE;
    // Transport Headers
    protected array $_transportHeaders = [
        'User-Agent' => 'PHP-JMAP-Client/1.0 (1.0; x64)',
		'Connection' => 'Keep-Alive',
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Content-Type' => 'application/json; charset=utf-8',
        'Accept' => 'application/json'
    ];
    // Transport Options
    protected array $_transportOptions = [
        'version' => self::TRANSPORT_VERSION_2,
        'timeout' => 30,
        'expect' => false,
        'verify' => true,
        'allow_redirects' => false,
        'cookies' => false,
        'decode_content' => true,
        'http_errors' => true,
        'proxy' => [],
        'curl' => [
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]
    ];
    // Transport Client
    protected ?GuzzleHttpClient $_client;
    // Transport Cookie
    protected ?string $_transportCookieJar = null;
    protected $_transportCookieStoreRetrieve = null;
    protected $_transportCookieStoreDeposit =  null;
    // Transport Request Retention
    protected bool $_transportRequestHeaderFlag = false;
    protected bool $_transportRequestBodyFlag = false;
    protected array $_transportRequestHeaderData = [];
    protected string $_transportRequestBodyData = '';
    // Transport Response Retention
    protected bool $_transportResponseHeaderFlag = false;
    protected bool $_transportResponseBodyFlag = false;
    protected array $_transportResponseHeaderData = [];
    protected string $_transportResponseBodyData = '';
    protected int $_transportResponseCode = 0;
    // Transport Logging
    protected bool $_transportLogState = false;
    protected string $_transportLogLocation = '/tmp/php-jmap.log';
    // Transport Redirect Max
    protected int $_transportRedirectAttempts = 3;
    // Service Response Types
    protected ResponseClasses $_ServiceResponseTypes;
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
        ?IAuthentication $authentication = null
    ) {

        $this->_ServiceResponseTypes = new ResponseClasses;

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
     * Constructs and configures a new Guzzle HTTP client instance with transport options
     */
    public function constructClient(array $overrides = []): GuzzleHttpClient {

        $options = $this->_transportOptions;

        $options = array_replace($options, $overrides);

        if ($this->_transportLogState || $this->_transportResponseHeaderFlag || $this->_transportResponseBodyFlag || $this->_transportRequestHeaderFlag || $this->_transportRequestBodyFlag) {
            $stack = HandlerStack::create();
            $stack->push($this->transmissionListener(), 'logger');
            $options['handler'] = $stack;
        }

        return new GuzzleHttpClient($options);
    }

    /**
     * Configures the HTTP protocol version used for transport
     */
    public function configureTransportVersion(int $value): void {
        $this->_transportOptions['version'] = $value;
    }
    
    /**
     * Configures the transport mode (http or https)
     */
    public function configureTransportMode(string $value): void {
        $this->_transportMode = match ($value) {
            'http' => self::TRANSPORT_MODE_STANDARD,
            'https' => self::TRANSPORT_MODE_SECURE,
            default => throw new InvalidArgumentException('Invalid transport mode'),
        };
    }

    /**
     * Configures custom transport options for the HTTP client
     */
    public function configureTransportOptions(array $options): void {
        $this->_transportOptions = array_replace($this->_transportOptions, $options);
    }

    /**
     * Configures SSL/TLS certificate verification for transport
     */
    public function configureTransportVerification(bool $value): void {
        $this->_transportOptions['curl'][CURLOPT_SSL_VERIFYPEER] = $value ? 1 : 0;
        $this->_transportOptions['curl'][CURLOPT_SSL_VERIFYHOST] = $value ? 2 : 0;
    }

    /**
     * Enables or disables transport logging
     */
    public function configureTransportLogState(bool $value): void {
        $this->_transportLogState = $value;
    }

    /**
     * Configures the file path where transport logs are written
     */
    public function configureTransportLogLocation(string $value): void {
        $this->_transportLogLocation = $value;
    }

    /**
     * Enables or disables retention of raw request headers sent
     */
    public function retainTransportRequestHeader(bool $value): void {
        $this->_transportRequestHeaderFlag = $value;
    }

    /**
     * Enables or disables retention of raw request body sent
     */
    public function retainTransportRequestBody(bool $value): void {
        $this->_transportRequestBodyFlag = $value;
    }

    /**
     * Enables or disables retention of raw response headers received
     */
    public function retainTransportResponseHeader(bool $value): void {
        $this->_transportResponseHeaderFlag = $value;
    }

    /**
     * Enables or disables retention of raw response body received
     */
    public function retainTransportResponseBody(bool $value): void {
        $this->_transportResponseBodyFlag = $value;
    }

    /**
     * Returns the last retained request headers that were sent
     */
    public function discloseTransportRequestHeader(): array {
        return $this->_transportRequestHeaderData;
    }

    /**
     * Returns the last retained request body that was sent
     */
    public function discloseTransportRequestBody(): string {
        return $this->_transportRequestBodyData;
    }

    /**
     * Returns the last retained HTTP response code that was received
     */
    public function discloseTransportResponseCode(): int {
        return $this->_transportResponseCode;
    }

    /**
     * Returns the last retained response headers that were received
     */
    public function discloseTransportResponseHeader(): array {
        return $this->_transportResponseHeaderData;
    }

    /**
     * Returns the last retained response body that was received
     */
    public function discloseTransportResponseBody(): string {
        return $this->_transportResponseBodyData;
    }

    /**
     * Sets the User-Agent header for transport requests
     */
    public function setTransportAgent(string $value): void {
        $this->_transportHeaders['User-Agent'] = $value;
    }

    /**
     * Gets the current User-Agent header value
     */
    public function getTransportAgent(): string {
        return $this->_transportHeaders['User-Agent'];
    }

    /**
     * Configures custom class types for command or parameter response mapping
     */
    public function configureClassTypes(string $collection, string $id, string $value) {

        if ($collection === 'command') {
            ResponseClasses::$Commands[$id] = $value;
        }elseif ($collection === 'parameters') {
            ResponseClasses::$Parameters[$id] = $value;
        }

    }

    /**
     * Gets the service host parameter
     */
    public function getHost(): string {
        return $this->_ServiceHost;
    }

    /**
     * Sets the service host parameter to be used for all requests
     */
    public function setHost(string $value): void {
        $this->_ServiceHost = $value;
        $this->_client = null;
    }

    /**
     * Gets the service discovery path
     */
    public function getDiscoveryPath(): string {
        return $this->_ServiceDiscoveryPath;
    }

    /**
     * Sets the service discovery path parameter to be used for initial connection
     */
    public function setDiscoveryPath(string $value): void {
        $this->_ServiceDiscoveryPath = $value;
        $this->_client = null;
    }

    /**
     * Gets the service discovery path
     */
    public function getCommandLocation(): string {
        return $this->_ServiceCommandLocation;
    }

    /**
     * Sets the service command location url
     */
    public function setCommandLocation(string $value): void {
        $this->_ServiceCommandLocation = $value;
    }

    /**
     * Gets the service blob/raw data download location url
     */
    public function getDownloadLocation(): string {
        return $this->_ServiceDownloadLocation;
    }

    /**
     * Sets the service blob/raw data download location url
     */
    public function setDownloadLocation(string $value): void {
        $this->_ServiceDownloadLocation = $value;
    }

    /**
     * Gets the service blob/raw data upload location url
     */
    public function getUploadLocation(): string {
        return $this->_ServiceUploadLocation;
    }

    /**
     * Sets the service blob/raw data upload location url
     */
    public function setUploadLocation(string $value): void {
        $this->_ServiceUploadLocation = $value;
    }

    /**
     * Gets the service events stream location url
     */
    public function getEventLocation(): string {
        return $this->_ServiceEventLocation;
    }

    /**
     * Sets the service events stream location url
     */
    public function setEventLocation(string $value): void {
        $this->_ServiceEventLocation = $value;
    }

    /**
     * Gets the authentication parameters object
     */
    public function getAuthentication(): IAuthentication {
        return $this->_ServiceAuthentication;
    }

    /**
     * Sets the authentication parameters to be used for all requests
     */
    public function setAuthentication(IAuthentication $value, bool $reset = true): void {
        
        // store parameter
        $this->_ServiceAuthentication = $value;
        // destroy existing client will need to be initialized again
        if ($reset) {
            $this->_client = null;
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
    public function connect(): Session {
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
    protected function authenticate(): ?array {
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
    protected function authenticateSimple(): ?array {

        $location = $this->_transportMode . $this->_ServiceHost . $this->_ServiceDiscoveryPath;

        $client = $this->constructClient([
            'base_uri' => $this->_transportMode . $this->_ServiceHost,
            'allow_redirects' => true
        ]);
        $headers = $this->_transportHeaders;
        // perform transmit and receive
        $response = $client->request('GET', $location, ['headers' => $headers]);
        $responseData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        $this->_client = $client;

        return $responseData;
    }

    /**
     * Performs JSON-based authentication with multi-step username and password exchange
     */
    protected function authenticateJson(): ?array {

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
        $clientOptions = $this->_transportOptions;
        $clientOptions['allow_redirects'] = true;

        if ($authentication instanceof IAuthenticationCookie) {
            $clientOptions['cookies'] = new CookieJar();
        }

        $client = $this->constructClient($clientOptions);

        /*===== perform initial transaction =====*/
        $requestData = json_encode((object)['type' => 'start']);
        $response = $client->request('POST', $location, ['headers' => $clientHeaders, 'body' => $requestData]);
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
            'loginId' => $serviceLoginId
        ]);
        $response = $client->request('POST', $location, ['headers' => $clientHeaders, 'body' => $requestData]);
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
            'loginId' => $serviceLoginId
        ]);
        $response = $client->request('POST', $location, ['headers' => $clientHeaders, 'body' => $requestData]);
        $responseData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        $this->_client = $client;

        if ($authentication instanceof IAuthenticationCookie && is_callable($authentication->getCookieStoreDeposit())) {
            $test = $authentication->getCookieStoreDeposit();
            $test($authentication->getCookieStoreId(), $client->getConfig('cookies')->toArray());
        }

        return $responseData;
    }

    /**
     * Performs cookie-based session authentication using stored cookie data
     */
    protected function authenticateSession(): ?array {

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
        $cookieJar = new CookieJar(false, $cookieData);

        $client = $this->constructClient([
            'allow_redirects' => true,
            'cookies' => $cookieJar
        ]);
        
        if (!empty($authentication->getCookieAuthenticationLocation())) {
            $location = $this->_transportMode . $this->_ServiceHost . $authentication->getCookieAuthenticationLocation();
        } else {
            $location = $this->_transportMode . $this->_ServiceHost . $this->_ServiceDiscoveryPath;
        }

        $headers = $this->_transportHeaders;
        unset($headers['Authentication']);

        $response = $client->request('GET', $location, ['headers' => $headers]);
        $responseData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        $this->_client = $client;

        if ($responseData[0]) {
            return $responseData[0];
        } else {
            return [];
        }

    }

    /**
     * Performs custom authentication using a user-defined authentication callable
     */
    protected function authenticateCustom(): ?array {

        // evaluate and validate authentication type
        if (!$this->_ServiceAuthentication instanceof IAuthenticationCustom) {
            return [];
        }
        if (!is_callable($this->_ServiceAuthentication->authenticate())) {
            return [];
        }
        $authenticate = $this->_ServiceAuthentication->authenticate();
        
        $client = $this->constructClient();
        $session = $authenticate($client);
        $this->_client = $client;
        return $session;

    }

    /**
     * Executes one or more JMAP commands and returns the response bundle
     */
    public function perform(RequestBundle|array $commands): ResponseBundle {

        // evaluate if command(s) was passed as a request bundled
        if ($commands instanceof RequestBundle){
            $request = $commands;
        }
        else {
            // construct request bundle object
            $request  = new RequestBundle(...$commands);
        }
        
        // serialize request
        $request = json_encode($request, JSON_UNESCAPED_SLASHES);
        // transmit and receive
        $response = $this->transceive($this->_ServiceCommandLocation, $request);
        // deserialize response
        $response = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        // convert jmap to typed classes
        if ($this->_ServiceResponseTypes !== null) {
            $response = new ResponseBundle($response, $this->_ServiceResponseTypes);
        }
        // return response
        return $response;

    }

    /**
     * Sends a request to the JMAP server and receives the response
     */
    public function transceive(string $uri, string $data): null|string {

        // evaluate if client is connected
        if (!$this->_SessionConnected) {
            $this->connect();
        }

        // reset requests
        $this->_transportRequestHeaderData = [];
        $this->_transportRequestBodyData = '';
        // reset responses
        $this->_transportResponseCode = 0;
        $this->_transportResponseHeaderData = [];
        $this->_transportResponseBodyData = '';

        // execute request
        $response = $this->_client->request('POST', $uri, ['headers' => $this->_transportHeaders, 'body' => $data]);
        $responseBody = $response->getBody()->getContents();
        
        return $responseBody;

    }

    /**
     * Downloads a blob/file from the JMAP server to a variable or stream resource
     */
    public function download(string $account, string $identifier, &$data, string $type = 'application/octet-stream', string $name = 'file.bin'): void {

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
        $location = str_replace("{accountId}", $account, $location);
        $location = str_replace("{blobId}", $identifier, $location);
        $location = str_replace("{type}", $type, $location);
        $location = str_replace("{name}", $name, $location);

        // modify specific headers
        $transportHeaders = $this->_transportHeaders;
        unset(
            $transportHeaders['Content-Type'],
            $transportHeaders['Accept']
        );

        // determine data destination and set receiving options
        if (is_resource($data)) {
            // execute request and write to resource stream
            $response = $this->_client->request('GET', $location, ['headers' => $transportHeaders, 'sink' => $data]);
        } else {
            // execute request and capture response body
            $response = $this->_client->request('GET', $location, ['headers' => $transportHeaders]);
            $data = $response->getBody()->getContents();
        }

    }

    /**
     * Uploads a blob/file to the JMAP server from a variable or stream resource
     */
    public function upload(string $account, string $type, &$data): string {
    
        // evaluate if client is connected
        if (!$this->_SessionConnected) {
            $this->connect();
        }

        // validate data
        if (is_resource($data) && get_resource_type($data) !== 'stream') {
            throw new InvalidArgumentException('Invalid resource passed');
        }

        // replace command options
        $location = str_replace("{accountId}", $account, $this->_ServiceUploadLocation);

        // modify specific headers
        $transportHeaders = $this->_transportHeaders;
        $transportHeaders['Content-Type'] = $type;

        // execute request
        $response = $this->_client->request('POST', $location, ['headers' => $transportHeaders, 'body' => $data]);
        $responseBody = $response->getBody()->getContents();

        return $responseBody;

    }

    /**
     * Creates a middleware function that logs and retains request/response transmission data
     */
    private function transmissionListener(): callable {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                // retain request headers
                if ($this->_transportRequestHeaderFlag) {
                    $this->_transportRequestHeaderData = $request->getHeaders();
                }
                // Optionally retain/log request body or URI
                if ($this->_transportRequestBodyFlag || $this->_transportLogState) {
                    $requestMessage = null;
                    $isStreamBody = is_resource($options['body'] ?? null);
                    if ($isStreamBody) {
                        $requestMessage = 'resource stream';
                    } else {
                        $body = $request->getBody();
                        $requestBody = '';
                        if ($body !== null) {
                            try {
                                $requestBody = $body->getContents();
                                if ($body->isSeekable()) {
                                    $body->rewind();
                                }
                            } catch (\Throwable $e) {
                                $requestBody = '';
                            }
                        }
                        if ($requestBody === '') {
                            $requestMessage = (string)$request->getUri();
                        } else {
                            $requestMessage = $requestBody;
                        }
                    }
                    if ($this->_transportRequestBodyFlag && $requestMessage !== null) {
                        $this->_transportRequestBodyData = $requestMessage;
                    }
                    if ($this->_transportLogState && $requestMessage !== null) {
                        $this->transmissionLogger('request', $requestMessage);
                    }
                }

                return $handler($request, $options)->then(
                    function (ResponseInterface $response) use ($request, $options) {
                        // retain response code
                        $this->_transportResponseCode = $response->getStatusCode();
                        // retain response header
                        if ($this->_transportResponseHeaderFlag) {
                            $this->_transportResponseHeaderData = $response->getHeaders();
                        }
                        // retain or log response body
                        if ($this->_transportResponseBodyFlag || $this->_transportLogState) {
                            $responseMessage = null;
                            $body = $response->getBody();
                            $responseBody = '';
                            try {
                                $responseBody = $body->getContents();
                                if ($body->isSeekable()) {
                                    $body->rewind();
                                }
                            } catch (\Throwable $e) {
                                $responseBody = '';
                            }

                            if ($responseBody === '' && is_resource($options['sink'] ?? null)) {
                                $responseMessage = '"resource stream"';
                            } else {
                                $responseMessage = $responseBody;
                            }
                            if ($this->_transportResponseBodyFlag && $responseMessage !== null) {
                                $this->_transportResponseBodyData = $responseMessage;
                            }
                            if ($this->_transportLogState && $responseMessage !== null) {
                                $this->transmissionLogger('response', $responseMessage);
                            }
                        }

                        return $response;
                    },
                    function ($reason) use ($request, $options) {
                        // Attempt to capture details even on HTTP exception
                        if ($reason instanceof RequestException && $reason->hasResponse()) {
                            $response = $reason->getResponse();
                            if ($response instanceof ResponseInterface) {
                                // retain response code
                                $this->_transportResponseCode = $response->getStatusCode();
                                // retain response header
                                if ($this->_transportResponseHeaderFlag) {
                                    $this->_transportResponseHeaderData = $response->getHeaders();
                                }
                                // retain or log response body
                                if ($this->_transportResponseBodyFlag || $this->_transportLogState) {
                                    $body = $response->getBody();
                                    $responseBody = '';
                                    try {
                                        $responseBody = $body->getContents();
                                        if ($body->isSeekable()) {
                                            $body->rewind();
                                        }
                                    } catch (\Throwable $e) {
                                        $responseBody = '';
                                    }
                                    if ($this->_transportResponseBodyFlag) {
                                        $this->_transportResponseBodyData = $responseBody;
                                    }
                                    if ($this->_transportLogState) {
                                        $this->transmissionLogger('response', $responseBody);
                                    }
                                }
                            }
                        }
                        throw $reason;
                    }
                );
            };
        };
    }

    /**
     * Writes transmission log entries to the configured log file location
     */
    private function transmissionLogger(string $type, string $message): void {
        
        // evaluate if logging is enabled
        if (!$this->_transportLogState) {
            return;
        }

        // format log entry with timestamp
        $logEntry = PHP_EOL . '{"date": "' . date("Y-m-d H:i:s.") . gettimeofday()["usec"] . '", "' . $type . '":' . $message . '}';

        // write to log file
        file_put_contents($this->_transportLogLocation, $logEntry, FILE_APPEND);

    }

    /**
     * Returns the current session connection status
     */
    public function sessionStatus(): bool {
    
        return $this->_SessionConnected;

    }

    /**
     * Returns the current session data object
     */
    public function sessionData(): ?Session {
    
        return $this->_SessionData;

    }

    /**
     * Checks if the session supports a specific capability
     */
    public function sessionCapable(string $value, bool $standard = true): bool {

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
    public function sessionCapabilities(?string $value = null, bool $standard = true): CapabilityCollection|null {
    
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
    public function sessionAccounts(): AccountCollection|null {
    
        if (!$this->_SessionData) {
            return null;
        }
        return $this->_SessionData->accounts();

    }

    /**
     * Returns the primary/default account for a specific capability or the core account
     */
    public function sessionAccountDefault(?string $value = null, bool $standard = true): Account|null {

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
