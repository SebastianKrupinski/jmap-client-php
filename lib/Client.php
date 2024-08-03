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

use JmapClient\Authentication\Basic;
use JmapClient\Authentication\Bearer;
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
    protected string $_TransportMode = self::TRANSPORT_MODE_SECURE;
    /**
     * Transpost Header
     */
    protected array $_TransportHeader = [
		'Connection' => 'Connection: Keep-Alive',
        'Cache-Control' => 'Cache-Control: no-cache, no-store, must-revalidate',
        'Content-Type' => 'Content-Type: application/json; charset=utf-8',
        'Accept' => 'Accept: application/json'
    ];
    /**
     * Transpost Options
     */
    protected array $_TransportOptions = [
        CURLOPT_USERAGENT => 'NextCloudJMAP/1.0 (1.0; x64)',
        CURLOPT_HTTP_VERSION => self::TRANSPORT_VERSION_2,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => 1,
        CURLOPT_SSL_VERIFYHOST => 1,
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
    protected bool $_TransportRequestHeaderFlag = false;

    /**
     * Retain Last Transport Request Body Flag
     *
     * @var bool
     */
    protected bool $_TransportRequestBodyFlag = false;

    /**
     * Last Transport Request Header Data
     *
     * @var string
     */
    protected string $_TransportRequestHeaderData = '';

    /**
     * Last Transport Request Body Data
     *
     * @var string
     */
    protected string $_TransportRequestBodyData = '';

    /**
     * Retain Last Transport Response Header Flag
     *
     * @var bool
     */
    protected bool $_TransportRepsonseHeaderFlag = false;

    /**
     * Retain Last Transport Response Body Flag
     *
     * @var bool
     */
    protected bool $_TransportRepsonseBodyFlag = false;

    /**
     * Last Transport Response Code
     *
     * @var string
     */
    protected string $_TransportRepsonseCode = '';

    /**
     * Last Transport Response Header Data
     *
     * @var string
     */
    protected string $_TransportRepsonseHeaderData = '';

    /**
     * Last Transport Response Header Data
     *
     * @var string
     */
    protected string $_TransportRepsonseBodyData = '';

    /**
     * Transport Logging State (ON/OFF)
     *
     * @var bool
     */
    protected bool $_TransportLogState = false;

    /**
     * Transport Log File Location
     *
     * @var string
     */
    protected string $_TransportLogLocation = '/tmp/php-jmap.log';

    /**
     * Transport Redirect Max
     *
     * @var int
     */
    protected int $_TransportRedirectAttempts = 3;

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
     * @var Basic|Bearer
     */
    protected $_ServiceAuthentication;

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
     * @param string $authentication    SErvice Authentication
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
        $this->_TransportOptions[CURLOPT_HTTP_VERSION] = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;

    }
    
    public function configureTransportMode(string $value): void {

        // store parameter
        $this->_TransportMode = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;

    }

    public function configureTransportOptions(array $options): void {

        // store parameter
        $this->_TransportOptions = array_replace($this->_TransportOptions, $options);
        // destroy existing client will need to be initilized again
        $this->_client = null;

    }

    public function configureTransportVerification(bool $value): void {

        // store parameter
        $this->_TransportOptions[CURLOPT_SSL_VERIFYPEER] = (int) $value;
        $this->_TransportOptions[CURLOPT_SSL_VERIFYHOST] = (int) $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;

    }

    /**
     * enables or disables transport log
     * 
     * @param bool $value           ture or false flag
     */
    public function configureTransportLogState(bool $value): void {

        // store parameter
        $this->_TransportLogState = $value;

    }

    /**
     * configures transport log location
     * 
     * @param bool $value           ture or false flag
     */
    public function configureTransportLogLocation(string $value): void {

        // store parameter
        $this->_TransportLogLocation = $value;

    }

    /**
     * Enables or disables retention of raw request headers sent
     * 
     * @param bool $value           ture or false flag
     */
    public function retainTransportRequestHeader(bool $value): void {
        $this->_TransportRequestHeaderFlag = $value;
    }

    /**
     * Enables or disables retention of raw request body sent
     * 
     * @param bool $value           ture or false flag
     */
    public function retainTransportRequestBody(bool $value): void {
        $this->_TransportRequestBodyFlag = $value;
    }

    /**
     * Enables or disables retention of raw response headers recieved
     * 
     * @param bool $value           ture or false flag
     */
    public function retainTransportResponseHeader(bool $value): void {
        $this->_TransportRepsonseHeaderFlag = $value;
    }

    /**
     * Enables or disables retention of raw response body recieved
     * 
     * @param bool $value           ture or false flag
     */
    public function retainTransportResponseBody(bool $value): void {
        $this->_TransportRepsonseBodyFlag = $value;
    }

    /**
     * returns last retained raw request header sent
     * 
     * @return string
     */
    public function discloseTransportRequestHeader(): string {
        return $this->_TransportRequestHeaderData;
    }

    /**
     * returns last retained raw request body sent
     * 
     * @return string
     */
    public function discloseTransportRequestBody(): string {
        return $this->_TransportRequestBodyData;
    }

    /**
     * returns last retained response code recieved
     * 
     * @return int
     */
    public function discloseTransportResponseCode(): int {
        return $this->_TransportRepsonseCode;
    }

    /**
     * returns last retained raw response header recieved
     * 
     * @return string
     */
    public function discloseTransportResponseHeader(): string {
        return $this->_TransportRepsonseHeaderData;
    }

    /**
     * returns last retained raw response body recieved
     * 
     * @return string
     */
    public function discloseTransportResponseBody(): string {
        return $this->_TransportRepsonseBodyData;
    }

    public function setTransportAgent(string $value): void {

        // store transport agent parameter
        $this->_TransportOptions[CURLOPT_USERAGENT] = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;

    }

    public function getTransportAgent(): string {

        // return transport agent paramater
        return $this->_TransportOptions[CURLOPT_USERAGENT];

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
        // destroy existing client will need to be initilized again
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
        // destroy existing client will need to be initilized again
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
     * @return Basic|AuthenticationBeare
     */
    public function getAuthentication(): Basic|Bearer {
        
        // return authentication information
        return $this->_ServiceAuthentication;

    }

     /**
     * Sets the authentication parameters to be used for all requests
     *
     * @param Basic|Bearer $value
     */
    public function setAuthentication(Basic|Bearer $value): void {
        
        // store parameter
        $this->_ServiceAuthentication = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;
        // set service basic authentication
        if ($this->_ServiceAuthentication instanceof Basic) {
            $this->_TransportOptions[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
            $this->_TransportOptions[CURLOPT_USERPWD] = $this->_ServiceAuthentication->Id . ':' . $this->_ServiceAuthentication->Secret;
        }
        // set service bearer authentication
        if ($this->_ServiceAuthentication instanceof Bearer) {
            unset($this->_TransportOptions[CURLOPT_HTTPAUTH]);
            $this->_TransportHeader['Authorization'] = 'Authorization: Bearer ' . $this->_ServiceAuthentication->Token;
        }

    }

    public function sessionStatus(): bool {
    
        return $this->_SessionConnected;

    }

    public function sessionData(): array {
    
        return $this->_SessionData;

    }

    public function sessionCapable(string $value): bool {

        return isset($this->_SessionData['capabilities']['urn:ietf:params:jmap:' . $value]) ? true : false;

    }

    public function sessionCapabilities(?string $value = null): array {
    
        if (!empty($value)) {
            return isset($this->_SessionData['capabilities']['urn:ietf:params:jmap:' . $value]) ?
                   $this->_SessionData['capabilities']['urn:ietf:params:jmap:' . $value] : 
                   [];
        } else {
            return $this->_SessionData['capabilities'];
        }

    }

    public function sessionAccounts(): array {
    
        return $this->_SessionData['accounts'];

    }

    public function sessionAccountDefault(?string $value = null): string | null {

        if (!empty($value)) {
            return isset($this->_SessionData['primaryAccounts']['urn:ietf:params:jmap:' . $value]) ?
                   $this->_SessionData['primaryAccounts']['urn:ietf:params:jmap:' . $value] : 
                   $this->_SessionData['primaryAccounts']['urn:ietf:params:jmap:core'];
        } else {
            return $this->_SessionData['primaryAccounts']['urn:ietf:params:jmap:core'];
        }

    }

    public function transceive(string $message): null|string {

        // evaluate if http client is initilized
        if (!isset($this->_client)) {
            $this->_client = curl_init();
        }
        // reset responses
        $this->_TransportRepsonseCode = 0;
        $this->_TransportRepsonseHeaderData = '';
        $this->_TransportRepsonseBodyData = '';
        // set request options
        curl_setopt_array($this->_client, $this->_TransportOptions);
        // set request header
        curl_setopt($this->_client, CURLOPT_HTTPHEADER, array_values($this->_TransportHeader));
        // set request data
        if (!empty($message)) {
            curl_setopt($this->_client, CURLOPT_POSTFIELDS, $message);
        }
        // evaluate, if we are retaining request headers
        if ($this->_TransportRequestHeaderFlag) { $this->_TransportRequestHeaderData = $header; }
        // evaluate, if we are retaining request body
        if ($this->_TransportRequestBodyFlag) { $this->_TransportRequestBodyData = $request; }
        // evaluate, if logging is enabled and write request to log
        if ($this->_TransportLogState) { 
            file_put_contents(
                $this->_TransportLogLocation, 
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
            if ($this->_TransportLogState) { 
                file_put_contents(
                    $this->_TransportLogLocation, 
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
            if ($this->_TransportLogState) { 
                file_put_contents(
                    $this->_TransportLogLocation, 
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
        $this->_TransportRepsonseCode = $code;
        // extract header size
        $header_size = curl_getinfo($this->_client, CURLINFO_HEADER_SIZE);
        // evaluate, if we are retaining response headers
        if ($this->_TransportRepsonseHeaderFlag || $code == 302) { $this->_TransportRepsonseHeaderData = substr($response, 0, $header_size); }
        // evaluate, if we are retaining response body
        if ($this->_TransportRepsonseBodyFlag) { $this->_TransportRepsonseBodyData = substr($response, $header_size); }
        // evaluate, if logging is enabled and write response body to log
        if ($this->_TransportLogState) { 
            file_put_contents(
                $this->_TransportLogLocation,
                PHP_EOL . date("Y-m-d H:i:s.").gettimeofday()["usec"] . ' - Response' . PHP_EOL . substr($response, $header_size) . PHP_EOL, 
                FILE_APPEND
            ); 
        }
        // return response
        return substr($response, $header_size);

    }

    public function connect(): array {

        // configure client for command
        unset($this->_TransportOptions[CURLOPT_POST]);
        $this->_TransportOptions[CURLOPT_HTTPGET];
        $this->_TransportOptions[CURLOPT_URL] = $this->_TransportMode . $this->_ServiceHost . $this->_ServiceDiscoveryPath;
        // 
        $RequestAttempt = true;
        $RequestAttempts = 0;
        while ($RequestAttempt && ($RequestAttempts < $this->_TransportRedirectAttempts)) {
            // Increment attempts
            $RequestAttempts++;
            $RequestAttempt = false;
            // perform transmit and recieve
            $session = $this->transceive('');
            // determine if request was redirected
            if ($this->_TransportRepsonseCode == 302) {
                foreach (explode("\r\n", trim($this->_TransportRepsonseHeaderData)) as $line) {
                    if (strpos($line, 'location:') !== false) {
                        $location = explode(':', $line, 2);
                        break;
                    }
                }
                if (isset($location) && !empty($location[1])) {
                    $RequestAttempt = true;
                    $this->_TransportOptions[CURLOPT_URL] = trim($location[1]);
                }
            }
        }
        // configure client to defaults
        $this->_TransportOptions[CURLOPT_POST] = true;
        unset($this->_TransportOptions[CURLOPT_CUSTOMREQUEST]);
        // convert text to object
        $session = json_decode($session, true, 512, JSON_THROW_ON_ERROR);
        // service locations
        $this->_ServiceCommandLocation = (isset($session['apiUrl'])) ? $session['apiUrl'] : '';
        $this->_ServiceDownloadLocation = (isset($session['downloadUrl'])) ? $session['downloadUrl'] : '';
        $this->_ServiceUploadLocation = (isset($session['uploadUrl'])) ? $session['uploadUrl'] : '';
        $this->_ServiceEventLocation = (isset($session['eventSourceUrl'])) ? $session['eventSourceUrl'] : '';
        // session meta data
        $this->_SessionData = $session;
        // session connected
        $this->_SessionConnected = true;
        // return response body
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
                $request->appendRequest($entry->namespace(), $entry);
            }
        }
        
        //
        // serialize request
        $request = $request->phrase();
        // assign transceiver location
        $this->_TransportOptions[CURLOPT_URL] = $this->_ServiceCommandLocation;
        // transmit and recieve
        $response = $this->transceive($request);
        // deserialize response
        $response = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        // construct response collection object
        $response = new ResponseBundle($response);
        // return response
        return $response;

    }

    public function download(string $account, string $identifier, string $type, string $name, string|\resource &$data): void {

        // assign transceiver location
        $this->_TransportOptions[CURLOPT_URL] = $this->_ServiceDownloadLocation;

    }

    public function upload(string $account, string $identifier, string $type, int $size, string|\resource &$data): void {
        
        // assign transceiver location
        $this->_TransportOptions[CURLOPT_URL] = $this->_ServiceUploadLocation;

    }

}
