<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2023 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Cookie\CookieJar;
use InvalidArgumentException;
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
 * JMAP Client
 */
class Client {
    /**
     * Transport Versions
     *
     * @var int
     */
    const TRANSPORT_VERSION_1 = 1.0;
    const TRANSPORT_VERSION_1_1 = 1.1;
    const TRANSPORT_VERSION_2 = 2.0;
    /**
     * Transport Modes
     *
     * @var string
     */
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
        'verify' => false,
        'allow_redirects' => false,
        'cookies' => false,
        'json' => true,
        'decode_content' => false,
        'proxy' => [],
        'curl' => [
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]
    ];
    // Transport Cookie
    protected ?string $_transportCookieJar = null;
    // Transport Client
    //protected CurlHandle|null $_client = null;
    protected ?GuzzleHttpClient $_client;
    // 
    protected $_transportCookieStoreRetrieve = null;
    // 
    protected $_transportCookieStoreDeposit =  null;
    // Retain Last Transport Request Header Flag
    protected bool $_transportRequestHeaderFlag = false;
    // Retain Last Transport Request Body Flag
    protected bool $_transportRequestBodyFlag = false;
    // Last Transport Request Header Data
    protected string $_transportRequestHeaderData = '';
    // Last Transport Request Body Data
    protected string $_transportRequestBodyData = '';
    // Retain Last Transport Response Header Flag
    protected bool $_transportResponseHeaderFlag = false;
    // Retain Last Transport Response Body Flag
    protected bool $_transportResponseBodyFlag = false;
    // Last Transport Response Code
    protected int $_transportResponseCode = 0;
    // Last Transport Response Header Data
    protected string $_transportResponseHeaderData = '';
    // Last Transport Response Header Data
    protected string $_transportResponseBodyData = '';
    // Transport Logging State (ON/OFF)
    protected bool $_transportLogState = false;
    // Transport Log File Location
    protected string $_transportLogLocation = '/tmp/php-jmap.log';
    // Transport Redirect Max
    protected int $_transportRedirectAttempts = 3;
    // Service Response Types
    protected ResponseClasses $_ServiceResponseTypes;
    // Service Host
    protected string $_ServiceHost = '';
    // Service Discovery Path
    protected string $_ServiceDiscoveryPath = '/.well-known/jmap';
    // Service Command Location
    protected string $_ServiceCommandLocation = '';
    // Service Download Location
    protected string $_ServiceDownloadLocation = '';
    // Service Upload Location
    protected string $_ServiceUploadLocation = '';
    // Service Event Location
    protected string $_ServiceEventLocation = '';
    // Authentication to use when connecting to the service
    protected IAuthentication $_ServiceAuthentication;
    // Session Connected Status
    protected bool $_SessionConnected = false;
    // Session data
    protected ?Session $_SessionData = null;
    
    /**
     * Constructor for the ExchangeWebServices class
     *
     * @param string $host       Service Host (FQDN, IPv4, IPv6)
     * @param $authentication    Service Authentication
     */
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

    public function constructClient(array $overrides = []): GuzzleHttpClient {

        $options = $this->_transportOptions;

        $options = array_replace($options, $overrides);

        return new GuzzleHttpClient($options);
    }

    public function configureTransportVersion(int $value): void {
        $this->_transportOptions['version'] = $value;
    }
    
    public function configureTransportMode(string $value): void {
        $this->_transportMode = match ($value) {
            'http' => self::TRANSPORT_MODE_STANDARD,
            'https' => self::TRANSPORT_MODE_SECURE,
            default => throw new InvalidArgumentException('Invalid transport mode'),
        };
    }

    public function configureTransportOptions(array $options): void {
        $this->_transportOptions = array_replace($this->_transportOptions, $options);
    }

    public function configureTransportVerification(bool $value): void {
        $this->_transportOptions['curl'][CURLOPT_SSL_VERIFYPEER] = $value ? 1 : 0;
        $this->_transportOptions['curl'][CURLOPT_SSL_VERIFYHOST] = $value ? 2 : 0;
    }

    /**
     * enables or disables transport log
     */
    public function configureTransportLogState(bool $value): void {
        $this->_transportLogState = $value;
    }

    /**
     * configures transport log location
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
     * Retains transport request or response data based on configured flags
     * 
     * @param string $source The source type: 'request' or 'response'
     * @param string $data The data to retain
     * 
     * @return void
     */
    private function retainTransportBody(string $source, string $data): void {
        
        // skip retention for resources
        if (is_resource($data)) {
            return;
        }
        // retain request body
        if ($source === 'request' && $this->_transportRequestBodyFlag) {
            $this->_transportRequestBodyData = $data;
        }
        // retain response body
        if ($source === 'response' && $this->_transportResponseBodyFlag) {
            $this->_transportResponseBodyData = $data;
        }

    }

    /**
     * Retains request headers to be sent
     * 
     * @param array $headers The request headers
     * 
     * @return void
     */
    private function retainRequestHeaders(array $headers): void {
        
        // skip retention if not enabled
        if (!$this->_transportRequestHeaderFlag) {
            return;
        }

        // format headers for storage
        $headerLines = [];
        foreach ($headers as $name => $value) {
            $headerLines[] = $name . ': ' . $value;
        }

        // store formatted headers
        $this->_transportRequestHeaderData = implode(PHP_EOL, $headerLines);

    }

    /**
     * Retains response headers from a response object
     * 
     * @param mixed $response The Guzzle response object
     * 
     * @return void
     */
    private function retainResponseHeaders($response): void {
        
        // skip retention if not enabled
        if (!$this->_transportResponseHeaderFlag) {
            return;
        }

        // format headers from response
        $headerLines = [];
        foreach ($response->getHeaders() as $name => $values) {
            $headerLines[] = $name . ': ' . implode(', ', $values);
        }

        // store formatted headers
        $this->_transportResponseHeaderData = implode(PHP_EOL, $headerLines);

    }

    /**
     * returns last retained raw request header sent
     */
    public function discloseTransportRequestHeader(): string {
        return $this->_transportRequestHeaderData;
    }

    /**
     * returns last retained raw request body sent
     */
    public function discloseTransportRequestBody(): string {
        return $this->_transportRequestBodyData;
    }

    /**
     * returns last retained response code received
     */
    public function discloseTransportResponseCode(): int {
        return $this->_transportResponseCode;
    }

    /**
     * returns last retained raw response header received
     */
    public function discloseTransportResponseHeader(): string {
        return $this->_transportResponseHeaderData;
    }

    /**
     * returns last retained raw response body received
     */
    public function discloseTransportResponseBody(): string {
        return $this->_transportResponseBodyData;
    }

    public function setTransportAgent(string $value): void {
        $this->_transportHeaders['User-Agent'] = $value;
    }

    public function getTransportAgent(): string {
        return $this->_transportHeaders['User-Agent'];
    }

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

    public function transceive(string $uri, string $message): null|string {

        // evaluate if http client is initialized
        if (!isset($this->_client)) {
            $this->_client = $this->constructClient();
        }
        // reset responses
        $this->_transportResponseCode = 0;
        $this->_transportResponseHeaderData = '';
        $this->_transportResponseBodyData = '';

        // retain request headers or body
        $this->retainRequestHeaders($this->_transportHeaders);
        $this->retainTransportBody('request', $message);
        // log request
        $this->logTransport('request', $message);

        // execute request
        $response = $this->_client->request('POST', $uri, ['headers' => $this->_transportHeaders, 'body' => $message]);
        $message = $response->getBody()->getContents();

        // retain response code
        $this->_transportResponseCode = $response->getStatusCode();
        // retain response headers or body
        $this->retainResponseHeaders($response);
        $this->retainTransportBody('response', $message);
        // log response
        $this->logTransport('response', $message);

        // return response
        return $message;

    }

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

    protected function authenticateSimple(): ?array {

        $location = $this->_transportMode . $this->_ServiceHost . $this->_ServiceDiscoveryPath;

        $client = new GuzzleHttpClient([
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
        $requestData = '{"type":"start"}';
        $response = $client->request('POST', $location, ['headers' => $clientHeaders, 'body' => $requestData]);
        $responseData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        // determine if login id was returned in initial request and fail if not
        if (!isset($responseData['loginId'])) {
            return null;
        }

        /*===== perform username exchange transaction =====*/
        $serviceLoginId = $responseData['loginId'];
        $requestData = sprintf('{"type":"username","username":"%s","loginId":"%s"}', $this->_ServiceAuthentication->getId(), $serviceLoginId);
        $response = $client->request('POST', $location, ['headers' => $clientHeaders, 'body' => $requestData]);
        $responseData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        // determine if login id was returned in initial request and fail if not
        if (!isset($responseData['loginId'])) {
            return null;
        }

        /*===== perform password exchange transaction =====*/
        $serviceLoginId = $responseData['loginId'];
        $requestData = sprintf('{"type":"password","value":"%s","remember":false,"loginId":"%s"}', $this->_ServiceAuthentication->getSecret(), $serviceLoginId);
        // perform transmit and receive
        $response = $client->request('POST', $location, ['headers' => $clientHeaders, 'body' => $requestData]);
        $responseData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        $this->_client = $client;

        if ($authentication instanceof IAuthenticationCookie && is_callable($authentication->getCookieStoreDeposit())) {
            $test = $authentication->getCookieStoreDeposit();
            $test($authentication->getCookieStoreId(), $client->getConfig('cookies')->toArray());
        }

        return $responseData;
    }

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

        $client = new GuzzleHttpClient([
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

    public function perform(RequestBundle|array $commands): ResponseBundle {

        // evaluate if command(s) was passed as a request bundled
        if ($commands instanceof RequestBundle){
            $request = $commands;
        }
        else {
            // construct request bundle object
            $request  = new RequestBundle();
            // append commands to bundle
            foreach ($commands as $entry) {
                $request->appendRequest($entry->getNamespace(), $entry);
            }
        }
        
        // serialize request
        $request = $request->phrase();
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

    public function download(string $account, string $identifier, &$data, string $type = 'application/octet-stream', string $name = 'file.bin'): void {

        // evaluate if http client is initialized
        if (!isset($this->_client)) {
            $this->_client = $this->constructClient();
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

        // retain request headers or body
        $this->retainRequestHeaders($transportHeaders);
        $this->retainTransportBody('request', $location);
        // log request
        $this->logTransport('request', $location);

        // determine data destination and set receiving options
        if (is_resource($data)) {
            // execute request and write to resource stream
            $response = $this->_client->request('GET', $location, ['headers' => $transportHeaders, 'sink' => $data]);
        } else {
            // execute request and capture response body
            $response = $this->_client->request('GET', $location, ['headers' => $transportHeaders]);
            $data = $response->getBody()->getContents();
        }

        // retain response code
        $this->_transportResponseCode = $response->getStatusCode();
        // retain response headers or body 
        $this->retainResponseHeaders($response);
        $this->retainTransportBody('response', (is_resource($data) ? 'resource stream' : $data));
        // log response
        $this->logTransport('response', (is_resource($data) ? 'resource stream' : $data));

    }

    public function upload(string $account, string $type, &$data): string {
    
        // evaluate if http client is initialized
        if (!isset($this->_client)) {
            $this->_client = $this->constructClient();
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

        // retain request headers or body
        $this->retainRequestHeaders($transportHeaders);
        $this->retainTransportBody('request', (is_resource($data) ? 'resource stream' : $data));
        // log request
        $this->logTransport('request', $location);

        // execute request
        $response = $this->_client->request('POST', $location, ['headers' => $transportHeaders, 'body' => $data]);
        $responseBody = $response->getBody()->getContents();

        // retain response code
        $this->_transportResponseCode = $response->getStatusCode();
        // retain response headers or body
        $this->retainResponseHeaders($response);
        $this->retainTransportBody('response', $responseBody);
        // log response
        $this->logTransport('response', $responseBody);

        return $responseBody;

    }

    /**
     * Logs transport activity to the configured log file
     * 
     * @param string $type The type of log entry (request, response, download, upload, etc)
     * @param string $message The message to log
     * 
     * @return void
     */
    private function logTransport(string $type, string $message): void {
        
        // evaluate if logging is enabled
        if (!$this->_transportLogState) {
            return;
        }

        // format log entry with timestamp
        $logEntry = PHP_EOL . '{"date": "' . date("Y-m-d H:i:s.") . gettimeofday()["usec"] . '", "' . $type . '":' . $message . '}';

        // write to log file
        file_put_contents($this->_transportLogLocation, $logEntry, FILE_APPEND);

    }

    public function sessionStatus(): bool {
    
        return $this->_SessionConnected;

    }

    public function sessionData(): ?Session {
    
        return $this->_SessionData;

    }

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

    public function sessionAccounts(): AccountCollection|null {
    
        if (!$this->_SessionData) {
            return null;
        }
        return $this->_SessionData->accounts();

    }

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
