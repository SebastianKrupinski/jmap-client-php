<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
* 
* @author Sebastian Krupinski <krupinski01@gmail.com>
*
* @license AGPL-3.0-or-later
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*/
namespace JmapClient;

use resource;
use RuntimeException;
use JmapClient\Authentication\IAuthentication;
use JmapClient\Authentication\Basic;
use JmapClient\Authentication\Bearer;
use JmapClient\Authentication\JsonBasic;
use JmapClient\Requests\Request;
use JmapClient\Requests\RequestBundle;
use JmapClient\Responses\ResponseBundle;
/**
 * JMAP Client
 */
class Client
{
    /**
     * Transport Versions
     *
     * @var int
     */
    const TRANSPORT_VERSION_1 = CURL_HTTP_VERSION_1_0;
    const TRANSPORT_VERSION_1_1 = CURL_HTTP_VERSION_1_1;
    const TRANSPORT_VERSION_2 = CURL_HTTP_VERSION_2_0;
    /**
     * Transport Modes
     *
     * @var string
     */
    const TRANSPORT_MODE_STANDARD = 'http://';
    const TRANSPORT_MODE_SECURE = 'https://';
    /**
     * Transport Mode
     *
     * @var string
     */
    protected string $_transportMode = self::TRANSPORT_MODE_SECURE;
    /**
     * Transport Header
     */
    protected array $_transportHeaders = [
		'Connection' => 'Connection: Keep-Alive',
        'Cache-Control' => 'Cache-Control: no-cache, no-store, must-revalidate',
        'Content-Type' => 'Content-Type: application/json; charset=utf-8',
        'Accept' => 'Accept: application/json'
    ];
    /**
     * Transport Options
     */
    protected array $_transportOptions = [
        CURLOPT_USERAGENT => 'PHP-JMAP-Client/1.0 (1.0; x64)',
        CURLOPT_HTTP_VERSION => self::TRANSPORT_VERSION_2,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => 1,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_POST => true,
        CURLOPT_CUSTOMREQUEST => null
    ];
    /**
     * cURL resource used to make the request
     *
     * @var CurlHandle
     */
    protected $_client;

    /**
     * Retain Last Transport Request Header Flag
     *
     * @var bool
     */
    protected bool $_transportRequestHeaderFlag = false;

    /**
     * Retain Last Transport Request Body Flag
     *
     * @var bool
     */
    protected bool $_transportRequestBodyFlag = false;

    /**
     * Last Transport Request Header Data
     *
     * @var string
     */
    protected string $_transportRequestHeaderData = '';

    /**
     * Last Transport Request Body Data
     *
     * @var string
     */
    protected string $_transportRequestBodyData = '';

    /**
     * Retain Last Transport Response Header Flag
     *
     * @var bool
     */
    protected bool $_transportResponseHeaderFlag = false;

    /**
     * Retain Last Transport Response Body Flag
     *
     * @var bool
     */
    protected bool $_transportResponseBodyFlag = false;

    /**
     * Last Transport Response Code
     *
     * @var string
     */
    protected string $_transportResponseCode = '';

    /**
     * Last Transport Response Header Data
     *
     * @var string
     */
    protected string $_transportResponseHeaderData = '';

    /**
     * Last Transport Response Header Data
     *
     * @var string
     */
    protected string $_transportResponseBodyData = '';

    /**
     * Transport Logging State (ON/OFF)
     *
     * @var bool
     */
    protected bool $_transportLogState = false;

    /**
     * Transport Log File Location
     *
     * @var string
     */
    protected string $_transportLogLocation = '/tmp/php-jmap.log';

    /**
     * Transport Redirect Max
     *
     * @var int
     */
    protected int $_transportRedirectAttempts = 3;

    /**
     * Service Host
     *
     * @var string
     */
    protected string $_ServiceHost = '';
    /**
     * Service Discovery Path
     *
     * @var string
     */
    protected string $_ServiceDiscoveryPath = '/.well-known/jmap';
    /**
     * Service Command URI
     *
     * @var string
     */
    protected string $_ServiceCommandLocation = '';
    /**
     * Service Download Location
     *
     * @var string
     */
    protected string $_ServiceDownloadLocation = '';
    /**
     * Service Upload URL
     *
     * @var string
     */
    protected string $_ServiceUploadLocation = '';
    /**
     * Service Event URL
     *
     * @var string
     */
    protected string $_ServiceEventLocation = '';
    /**
     * Authentication to use when connecting to the service
     *
     * @var IAuthentication
     */
    protected IAuthentication $_ServiceAuthentication;

    /**
     * Session Connected
     *
     * @var bool
     */
    protected bool $_SessionConnected = false;
    /**
     * Session meta data
     *
     * @var bool
     */
    protected array $_SessionData = [];
    
    
    /**
     * Constructor for the ExchangeWebServices class
     *
     * @param string $host              Service Host (FQDN, IPv4, IPv6)
     * @param $authentication    Service Authentication
     */
    public function __construct(
        $host = '',
        $authentication = null
    ) {

        // set service host
        if (!empty($host)) {
            $this->setHost($host);
        }
        // set service authentication
        if (isset($authentication)) {
            $this->setAuthentication($authentication);
        }

    }

    public function configureTransportVersion(int $value): void {
        
        // store parameter
        $this->_transportOptions[CURLOPT_HTTP_VERSION] = $value;
        // destroy existing client will need to be initialized again
        $this->_client = null;

    }
    
    public function configureTransportMode(string $value): void {

        // store parameter
        $this->_transportMode = $value;
        // destroy existing client will need to be initialized again
        $this->_client = null;

    }

    public function configureTransportOptions(array $options): void {

        // store parameter
        $this->_transportOptions = array_replace($this->_transportOptions, $options);
        // destroy existing client will need to be initialized again
        $this->_client = null;

    }

    public function configureTransportVerification(bool $value): void {

        // store parameter
        $this->_transportOptions[CURLOPT_SSL_VERIFYPEER] = $value ? 1 : 0;
        $this->_transportOptions[CURLOPT_SSL_VERIFYHOST] = $value ? 2 : 0;
        // destroy existing client will need to be initialized again
        $this->_client = null;

    }

    /**
     * enables or disables transport log
     * 
     * @param bool $value           true or false flag
     */
    public function configureTransportLogState(bool $value): void {

        // store parameter
        $this->_transportLogState = $value;

    }

    /**
     * configures transport log location
     * 
     * @param bool $value           true or false flag
     */
    public function configureTransportLogLocation(string $value): void {

        // store parameter
        $this->_transportLogLocation = $value;

    }

    /**
     * Enables or disables retention of raw request headers sent
     * 
     * @param bool $value           true or false flag
     */
    public function retainTransportRequestHeader(bool $value): void {
        $this->_transportRequestHeaderFlag = $value;
    }

    /**
     * Enables or disables retention of raw request body sent
     * 
     * @param bool $value           true or false flag
     */
    public function retainTransportRequestBody(bool $value): void {
        $this->_transportRequestBodyFlag = $value;
    }

    /**
     * Enables or disables retention of raw response headers received
     * 
     * @param bool $value           true or false flag
     */
    public function retainTransportResponseHeader(bool $value): void {
        $this->_transportResponseHeaderFlag = $value;
    }

    /**
     * Enables or disables retention of raw response body received
     * 
     * @param bool $value           true or false flag
     */
    public function retainTransportResponseBody(bool $value): void {
        $this->_transportResponseBodyFlag = $value;
    }

    /**
     * returns last retained raw request header sent
     * 
     * @return string
     */
    public function discloseTransportRequestHeader(): string {
        return $this->_transportRequestHeaderData;
    }

    /**
     * returns last retained raw request body sent
     * 
     * @return string
     */
    public function discloseTransportRequestBody(): string {
        return $this->_transportRequestBodyData;
    }

    /**
     * returns last retained response code received
     * 
     * @return int
     */
    public function discloseTransportResponseCode(): int {
        return $this->_transportResponseCode;
    }

    /**
     * returns last retained raw response header received
     * 
     * @return string
     */
    public function discloseTransportResponseHeader(): string {
        return $this->_transportResponseHeaderData;
    }

    /**
     * returns last retained raw response body received
     * 
     * @return string
     */
    public function discloseTransportResponseBody(): string {
        return $this->_transportResponseBodyData;
    }

    public function setTransportAgent(string $value): void {

        // store transport agent parameter
        $this->_transportOptions[CURLOPT_USERAGENT] = $value;
        // destroy existing client will need to be initialized again
        $this->_client = null;

    }

    public function getTransportAgent(): string {

        // return transport agent parameter
        return $this->_transportOptions[CURLOPT_USERAGENT];

    }

    

    /**
     * Gets the service host parameter
     *
     * @return string
     */
    public function getTransportSessionCookie(): string {
        
        // return service host parameter
        return $this->_ServiceHost;

    }

    /**
     * Sets the service host parameter to be used for all requests
     *
     * @param string $value
     */
    public function setTransportSessionCookie(string $value): void {

        // store service host
        $this->_ServiceHost = $value;
        // destroy existing client will need to be initialized again
        $this->_client = null;

    }

    /**
     * Gets the service host parameter
     *
     * @return string
     */
    public function getHost(): string {
        
        // return service host parameter
        return $this->_ServiceHost;

    }

    /**
     * Sets the service host parameter to be used for all requests
     *
     * @param string $value
     */
    public function setHost(string $value): void {

        // store service host
        $this->_ServiceHost = $value;
        // destroy existing client will need to be initialized again
        $this->_client = null;

    }

    /**
     * Gets the service discovery path
     *
     * @return string
     */
    public function getDiscoveryPath(): string {
        
        // return service discovery path parameter
        return $this->_ServiceDiscoveryPath;

    }

    /**
     * Sets the service discovery path parameter to be used for initial connection
     *
     * @param string $value
     */
    public function setDiscoveryPath(string $value): void {

        // store service path parameter
        $this->_ServiceDiscoveryPath = $value;
        // destroy existing client will need to be initialized again
        $this->_client = null;

    }

    /**
     * Gets the service discovery path
     *
     * @return string
     */
    public function getCommandLocation(): string {

        return $this->_ServiceCommandLocation;

    }

    /**
     * Sets the service command location url
     *
     * @param string $value
     */
    public function setCommandLocation(string $value): void {

        $this->_ServiceCommandLocation = $value;

    }

    /**
     * Gets the service blob/raw data download location url
     *
     * @return string
     */
    public function getDownloadLocation(): string {

        return $this->_ServiceDownloadLocation;

    }

    /**
     * Sets the service blob/raw data download location url
     *
     * @param string $value
     */
    public function setDownloadLocation(string $value): void {

        $this->_ServiceDownloadLocation = $value;

    }

    /**
     * Gets the service blob/raw data upload location url
     *
     * @return string
     */
    public function getUploadLocation(): string {

        return $this->_ServiceUploadLocation;

    }

    /**
     * Sets the service blob/raw data upload location url
     *
     * @param string $value
     */
    public function setUploadLocation(string $value): void {

        $this->_ServiceUploadLocation = $value;

    }

    /**
     * Gets the service events stream location url
     *
     * @return string
     */
    public function getEventLocation(): string {

        return $this->_ServiceEventLocation;

    }

    /**
     * Sets the service events stream location url
     *
     * @param string $value
     */
    public function setEventLocation(string $value): void {

        $this->_ServiceEventLocation = $value;

    }

     /**
     * Gets the authentication parameters object
     *
     * @return IAuthentication
     */
    public function getAuthentication(): IAuthentication {
        
        // return authentication information
        return $this->_ServiceAuthentication;

    }

     /**
     * Sets the authentication parameters to be used for all requests
     *
     * @param IAuthentication $value
     */
    public function setAuthentication(IAuthentication $value, bool $reset = true): void {
        
        // store parameter
        $this->_ServiceAuthentication = $value;
        // destroy existing client will need to be initialized again
        if ($reset) {
            $this->_client = null;
        }
        // set service json basic authentication
        if ($this->_ServiceAuthentication instanceof None) {
            unset($this->_transportOptions[CURLOPT_HTTPAUTH]);
            unset($this->_transportHeaders['Authorization']);
        }
        // set service json basic authentication
        if ($this->_ServiceAuthentication instanceof JsonBasic) {
            unset($this->_transportOptions[CURLOPT_HTTPAUTH]);
            unset($this->_transportHeaders['Authorization']);
        }
        // set service basic authentication
        if ($this->_ServiceAuthentication instanceof Basic) {
            unset($this->_transportHeaders['Authorization']);
            $this->_transportOptions[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
            $this->_transportOptions[CURLOPT_USERPWD] = $this->_ServiceAuthentication->Id . ':' . $this->_ServiceAuthentication->Secret;
        }
        // set service bearer authentication
        if ($this->_ServiceAuthentication instanceof Bearer) {
            unset($this->_transportOptions[CURLOPT_HTTPAUTH]);
            $this->_transportHeaders['Authorization'] = 'Authorization: Bearer ' . $this->_ServiceAuthentication->Token;
        }

    }

    public function sessionStatus(): bool {
    
        return $this->_SessionConnected;

    }

    public function sessionData(): array {
    
        return $this->_SessionData;

    }

    public function sessionCapable(string $value, bool $standard = true): bool {

        if ($standard === false) {
            return isset($this->_SessionData['capabilities'][$value]) ? true : false;
        } else {
            return isset($this->_SessionData['capabilities']['urn:ietf:params:jmap:' . $value]) ? true : false;
        }

    }

    public function sessionCapabilities(?string $value = null, bool $standard = true): array {
    
        if ($standard === true && !empty($value)) {
            return isset($this->_SessionData['capabilities']['urn:ietf:params:jmap:' . $value]) ?
                   $this->_SessionData['capabilities']['urn:ietf:params:jmap:' . $value] : [];
        } elseif ($standard === false && !empty($value)) {
            return isset($this->_SessionData['capabilities'][$value]) ?
                   $this->_SessionData['capabilities'][$value] : [];
        } else {
            return $this->_SessionData['capabilities'];
        }

    }

    public function sessionAccounts(): array {
    
        return $this->_SessionData['accounts'];

    }

    public function sessionAccountDefault(?string $value = null, bool $standard = true): string | null {

        if ($standard === true && !empty($value)) {
            return isset($this->_SessionData['primaryAccounts']['urn:ietf:params:jmap:' . $value]) ?
                   $this->_SessionData['primaryAccounts']['urn:ietf:params:jmap:' . $value] : null;
        } elseif ($standard === false && !empty($value)) {
            return isset($this->_SessionData['primaryAccounts'][$value]) ?
                   $this->_SessionData['primaryAccounts'][$value] : null;
        } else {
            return $this->_SessionData['primaryAccounts']['urn:ietf:params:jmap:core'];
        }

    }

    public function transceive(string $message): null|string {

        // evaluate if http client is initialized
        if (!isset($this->_client)) {
            $this->_client = curl_init();
        }
        // reset responses
        $this->_transportResponseCode = 0;
        $this->_transportResponseHeaderData = '';
        $this->_transportResponseBodyData = '';
        // set request options
        curl_setopt_array($this->_client, $this->_transportOptions);
        // set request header
        curl_setopt($this->_client, CURLOPT_HTTPHEADER, array_values($this->_transportHeaders));
        // set request data
        if (!empty($message)) {
            curl_setopt($this->_client, CURLOPT_POSTFIELDS, $message);
        }
        // evaluate, if we are retaining request headers
        if ($this->_transportRequestHeaderFlag) { $this->_transportRequestHeaderData = $header; }
        // evaluate, if we are retaining request body
        if ($this->_transportRequestBodyFlag) { $this->_transportRequestBodyData = $request; }
        // evaluate, if logging is enabled and write request to log
        if ($this->_transportLogState) { 
            file_put_contents(
                $this->_transportLogLocation, 
                PHP_EOL . date("Y-m-d H:i:s.").gettimeofday()["usec"] . ' - Request' . PHP_EOL . $message . PHP_EOL, 
                FILE_APPEND
            );
        }

        // execute request
        $response = curl_exec($this->_client);
        // evaluate execution errors
        $code = curl_errno($this->_client);
        if ($code > 0) {
            // evaluate, if logging is enabled and write error to log
            if ($this->_transportLogState) { 
                file_put_contents(
                    $this->_transportLogLocation, 
                    PHP_EOL . date("Y-m-d H:i:s.").gettimeofday()["usec"] . ' - Error' . PHP_EOL . curl_error($this->_client) . PHP_EOL . $response . PHP_EOL, 
                    FILE_APPEND
                );
            }
            // thrown exception
            throw new RuntimeException(curl_error($this->_client), $code);
        }

        // evaluate http responses
        $code = (int) curl_getinfo($this->_client, CURLINFO_RESPONSE_CODE);
        if ($code > 400) {
            // evaluate, if logging is enabled and write error to log
            if ($this->_transportLogState) { 
                file_put_contents(
                    $this->_transportLogLocation, 
                    PHP_EOL . date("Y-m-d H:i:s.").gettimeofday()["usec"] . ' - Error' . PHP_EOL . $response . PHP_EOL, 
                    FILE_APPEND
                );
            }
            // thrown exception
            switch ($code) {
                case 401:
                    throw new RuntimeException('Unauthorized', $code);
                    break;
                case 403:
                    throw new RuntimeException('Forbidden', $code);
                    break;
                case 404:
                    throw new RuntimeException('Not Found', $code);
                    break;
                case 408:
                    throw new RuntimeException('Request Timeout', $code);
                    break;
            }
        }
        // retain response code
        $this->_transportResponseCode = $code;
        // extract header size
        $header_size = curl_getinfo($this->_client, CURLINFO_HEADER_SIZE);
        // evaluate, if we are retaining response headers
        if ($this->_transportResponseHeaderFlag || $code == 302) { $this->_transportResponseHeaderData = substr($response, 0, $header_size); }
        // evaluate, if we are retaining response body
        if ($this->_transportResponseBodyFlag) { $this->_transportResponseBodyData = substr($response, $header_size); }
        // evaluate, if logging is enabled and write response body to log
        if ($this->_transportLogState) { 
            file_put_contents(
                $this->_transportLogLocation,
                PHP_EOL . date("Y-m-d H:i:s.").gettimeofday()["usec"] . ' - Response' . PHP_EOL . substr($response, $header_size) . PHP_EOL, 
                FILE_APPEND
            ); 
        }
        // return response
        return substr($response, $header_size);

    }

    public function connect(): array {
        // authenticate and retrieve json session
        $this->_SessionData = $session = $this->authenticate();
        // service authentication
        if (isset($session['accessToken'])) {
            $this->setAuthentication(new Bearer($this->_ServiceAuthentication->Id, $session['accessToken'], 0));
        }
        // service locations
        $this->_ServiceCommandLocation = (isset($session['apiUrl'])) ? $session['apiUrl'] : '';
        $this->_ServiceDownloadLocation = (isset($session['downloadUrl'])) ? $session['downloadUrl'] : '';
        $this->_ServiceUploadLocation = (isset($session['uploadUrl'])) ? $session['uploadUrl'] : '';
        $this->_ServiceEventLocation = (isset($session['eventSourceUrl'])) ? $session['eventSourceUrl'] : '';
        // session connected
        $this->_SessionConnected = true;
        // return response body
        return $session;

    }

    protected function authenticate(): ?array {
        // perform initial authentication
        if ($this->_ServiceAuthentication instanceof JsonBasic) {
            return $this->authenticateJson();
        } else {
            return $this->authenticateBasic();
        }
    }

    protected function authenticateBasic(): ?array {
        // configure client for command
        $this->_transportOptions[CURLOPT_URL] = $this->_transportMode . $this->_ServiceHost . $this->_ServiceDiscoveryPath;
        $this->_transportOptions[CURLOPT_HTTPGET];
        unset($this->_transportOptions[CURLOPT_POST]);
        // 
        $RequestAttempt = true;
        $RequestAttempts = 0;
        while ($RequestAttempt && ($RequestAttempts < $this->_transportRedirectAttempts)) {
            // Increment attempts
            $RequestAttempts++;
            $RequestAttempt = false;
            // perform transmit and receive
            $response = $this->transceive('');
            // determine if request was redirected
            if ($this->_transportResponseCode == 302) {
                foreach (explode("\r\n", trim($this->_transportResponseHeaderData)) as $line) {
                    if (strpos($line, 'location:') !== false) {
                        $location = explode(':', $line, 2);
                        break;
                    }
                }
                if (isset($location) && !empty($location[1])) {
                    $RequestAttempt = true;
                    $this->_transportOptions[CURLOPT_URL] = trim($location[1]);
                }
            }
        }
        // configure client to defaults
        $this->_transportOptions[CURLOPT_POST] = true;
        unset($this->_transportOptions[CURLOPT_CUSTOMREQUEST]);

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    protected function authenticateJson(): ?array {

        $cookie_jar = tempnam('/tmp','');
        // configure client transport settings
        $this->_transportOptions[CURLOPT_URL] = $this->_transportMode . $this->_ServiceHost . $this->_ServiceDiscoveryPath;
        $this->_transportOptions[CURLOPT_POST] = true;
        unset($this->_transportOptions[CURLOPT_CUSTOMREQUEST]);
        $this->retainTransportResponseHeader(true);

        /*===== perform initial transaction =====*/
        $this->_transportOptions[CURLOPT_COOKIEJAR] = $cookie_jar;
        $requestData = '{"type":"start"}';
        $RequestAttempt = true;
        $RequestAttempts = 0;
        while ($RequestAttempt && ($RequestAttempts < $this->_transportRedirectAttempts)) {
            // Increment attempts
            $RequestAttempts++;
            $RequestAttempt = false;
            // perform transmit and receive
            $responseData = $this->transceive($requestData);
            // determine if request was redirected
            if ($this->_transportResponseCode == 302) {
                foreach (explode("\r\n", trim($this->_transportResponseHeaderData)) as $line) {
                    if (strpos($line, 'location:') !== false) {
                        $location = explode(':', $line, 2);
                        break;
                    }
                }
                if (isset($location) && !empty($location[1])) {
                    $RequestAttempt = true;
                    $this->_transportOptions[CURLOPT_URL] = trim($location[1]);
                }
            }
        }
        $responseData = json_decode($responseData, true, 512, JSON_THROW_ON_ERROR);
        // determine if login id was returned in initial request and fail if not
        if (!isset($responseData['loginId'])) {
            return null;
        }

        /*===== perform username exchange transaction =====*/
        unset($this->_transportOptions[CURLOPT_COOKIEJAR]);
        $this->_transportOptions[CURLOPT_COOKIEFILE] = $cookie_jar;
        $serviceLoginId = $responseData['loginId'];
        $requestData = sprintf('{"type":"username","username":"%s","loginId":"%s"}', $this->_ServiceAuthentication->Id, $serviceLoginId);
        // perform transmit and receive
        $responseData = $this->transceive($requestData);
        $responseData = json_decode($responseData, true, 512, JSON_THROW_ON_ERROR);
        // determine if login id was returned in initial request and fail if not
        if (!isset($responseData['loginId'])) {
            return null;
        }

        /*===== perform password exchange transaction =====*/
        $this->_transportOptions[CURLOPT_COOKIEFILE] = $cookie_jar;
        $serviceLoginId = $responseData['loginId'];
        $requestData = sprintf('{"type":"password","value":"%s","remember":false,"loginId":"%s"}', $this->_ServiceAuthentication->Secret, $serviceLoginId);
        // perform transmit and receive
        $responseData = $this->transceive($requestData);
        $responseData = json_decode($responseData, true, 512, JSON_THROW_ON_ERROR);

        return $responseData;
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
                $request->appendRequest($entry->namespace(), $entry);
            }
        }
        
        //
        // serialize request
        $request = $request->phrase();
        // assign transceiver location
        $this->_transportOptions[CURLOPT_URL] = $this->_ServiceCommandLocation;
        // transmit and receive
        $response = $this->transceive($request);
        // deserialize response
        $response = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        // construct response collection object
        $response = new ResponseBundle($response);
        // return response
        return $response;

    }

    public function download(string $account, string $identifier, &$data, string $type = 'application/octet-stream', string $name = 'file.bin'): void {

        // replace command options
        $location = $this->_ServiceDownloadLocation;
        $location = str_replace("{accountId}", $account, $location);
        $location = str_replace("{blobId}", $identifier, $location);
        $location = str_replace("{type}", $type, $location);
        $location = str_replace("{name}", $name, $location);
        // clone default headers
        $transportHeaders = $this->_transportHeaders;
        // remove some headers
        unset(
            $transportHeaders['Content-Type'],
            $transportHeaders['Accept'],
        );
        // set specific headers
        $transportHeaders['Connection'] = 'Connection: Close';
        // clone default options
        $transportOptions = $this->_transportOptions;
        // remove some options
        unset(
            $transportOptions[CURLOPT_POST],
            $transportOptions[CURLOPT_CUSTOMREQUEST],
            $transportOptions[CURLOPT_RETURNTRANSFER]
        );
        // set specific options
        $transportOptions[CURLOPT_HTTPHEADER] = array_values($transportHeaders);
        $transportOptions[CURLOPT_HTTPGET];
        $transportOptions[CURLOPT_URL] = $location;
        // determine data destiantion and set recieving options
        if (is_resource($data)) {
            if (get_resource_type($data) !== 'stream') {
                throw new InvalidArgumentException('Invalid resource passed');
            }
            $transportOptions[CURLOPT_FILE] = $data;
            $transportOptions[CURLOPT_HEADER] = 0;
        } else {
            $transportOptions[CURLOPT_RETURNTRANSFER] = true;
        }
        // initilize client
        $client = curl_init();
        // set request options
        curl_setopt_array($client, $transportOptions);
        // execute request for different methods
        if (is_resource($data)) {
            curl_exec($client);
        } else {
            $response = curl_exec($client);
            // extract header size
            $header_size = curl_getinfo($client, CURLINFO_HEADER_SIZE);
            // extract data
            $data = substr($response, $header_size);
        }
        // close client
        curl_close($client);

    }

    public function upload(string $account, string $type, &$data): string {
    
        // replace command options
        $location = str_replace("{accountId}", $account, $this->_ServiceUploadLocation);
        // clone default headers
        $transportHeaders = $this->_transportHeaders;
        // remove some headers
        unset(
            $transportHeaders['Accept'],
        );
        // set specific headers
        $transportHeaders['Connection'] = 'Connection: Close';
        $transportHeaders['Content-Type'] = 'Content-Type: ' . $type;
        // clone default transport options
        $transportOptions = $this->_transportOptions;
        // remove some transport options
        unset(
            $transportOptions[CURLOPT_CUSTOMREQUEST],
        );
        // set specific options
        $transportOptions[CURLOPT_HTTPHEADER] = array_values($transportHeaders);
        $transportOptions[CURLOPT_URL] = $location;
        $transportOptions[CURLOPT_POST] = true;
        // determine data source and set sending options
        if (is_resource($data)) {
            if (get_resource_type($data) !== 'stream') {
                throw new InvalidArgumentException('Invalid resource passed');
            }
            $transportOptions[CURLOPT_INFILESIZE] = $size;
            $transportOptions[CURLOPT_INFILE] = $data; // ($in=fopen($tmpFile, 'r'))
        }
        else {
            $transportOptions[CURLOPT_POSTFIELDS] = $data;
        }
        // initialize client
        $client = curl_init();
        // set request options
        curl_setopt_array($client, $transportOptions);
        // execute request
        $response = curl_exec($client);
        // extract header size
        $header_size = curl_getinfo($client, CURLINFO_HEADER_SIZE);
        // close client
        curl_close($client);
        // return response
        return substr($response, $header_size);

    }

}
