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

        // clear last headers and response
        $this->_ResponseHeaders = '';
        $this->_ResponseData = '';
        // evaluate if http client is initilized and location is the same
        if (!isset($this->_client)) {
            $this->_client = curl_init();
        }
        curl_setopt_array($this->_client, $this->_TransportOptions);
        curl_setopt($this->_client, CURLOPT_HTTPHEADER, array_values($this->_TransportHeader));
        // set request data
        if (!empty($message)) {
            curl_setopt($this->_client, CURLOPT_POSTFIELDS, $message);
        }
        // execute request
        $this->_ResponseData = curl_exec($this->_client);

        // evealuate execution errors
        $code = curl_errno($this->_client);
        if ($code > 0) {
            throw new \RuntimeException(curl_error($this->_client), $code);
        }

        // evaluate http responses
        $code = (int) curl_getinfo($this->_client, CURLINFO_RESPONSE_CODE);
        if ($code > 400) {
            switch ($code) {
                case 401:
                    throw new \RuntimeException('Unauthorized', $code);
                    break;
                case 403:
                    throw new \RuntimeException('Forbidden', $code);
                    break;
                case 404:
                    throw new \RuntimeException('Not Found', $code);
                    break;
                case 408:
                    throw new \RuntimeException('Request Timeout', $code);
                    break;
            }
        }

        // separate headers and body
        $size = curl_getinfo($this->_client, CURLINFO_HEADER_SIZE);
        $this->_ResponseHeaders = substr($this->_ResponseData, 0, $size);
        $this->_ResponseData = substr($this->_ResponseData, $size);
        // return body
        return $this->_ResponseData;

    }

    public function connect(): array {

        // configure client for command
        unset($this->_TransportOptions[CURLOPT_POST]);
        $this->_TransportOptions[CURLOPT_HTTPGET];
        $this->_TransportOptions[CURLOPT_URL] = $this->_TransportMode . $this->_ServiceHost . $this->_ServiceDiscoveryPath;
        // perform transmit and recieve
        $session = $this->transceive('');
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
