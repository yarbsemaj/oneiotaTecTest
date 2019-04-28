<?php
namespace core;

use InvalidArgumentException;

/**
 * Represents an HTTP response
 *
 **/
class Response {

	const RESPONSE_TYPE_JSON = 'application/json';
	const RESPONSE_TYPE_JS = 'application/javascript';
	const RESPONSE_TYPE_XML = 'application/xhtml+xml';
	const RESPONSE_TYPE_HTML = 'text/html';
	const RESPONSE_TYPE_PNG = 'image/png';
	const RESPONSE_TYPE_JPG = 'image/jpeg';
	const RESPONSE_TYPE_CSV = 'text/csv';
	const RESPONSE_TYPE_TXT = 'text/plain';

	private static $supportedResponseTypes = array(
		 self::RESPONSE_TYPE_JSON,
		 self::RESPONSE_TYPE_JS,
		 self::RESPONSE_TYPE_XML,
		 self::RESPONSE_TYPE_HTML,
		 self::RESPONSE_TYPE_PNG,
		 self::RESPONSE_TYPE_JPG,
		 self::RESPONSE_TYPE_CSV,
		 self::RESPONSE_TYPE_TXT
	);

	private static $HTTPStatusCodes = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => '(Unused)',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported'
	);

	protected $additionalHeaders = array();

	/**
	 * The currently set HTTP status code.
	 *
	 * @var int
	 */
	protected $statusCode;

	/**
	 * The currently set HTTP response type.
	 *
	 * @var int
	 */
	protected $responseType;

	/**
	 * Body of the response to be returned verbatim.
	 *
	 * @var string
	 */
	protected $body;

	/**
	 * URI to be put in the location header of the response.
	 *
	 * @var string
	 */
	protected $location;

	/**
	 * Constructor
	 *
	 * @param int $statusCode HTTP status code to use.
	 */
	public function __construct() {
		$this->setResponseType(self::RESPONSE_TYPE_HTML);
	}

	/**
	 * Set the HTTP status code to be used.
	 *
	 * @return void
	 **/
	public function setStatusCode($code){
		if(!isset(self::$HTTPStatusCodes[$code])){
			$this->statusCode = 400;
		}
		else $this->statusCode = (int) $code;
	}


	/**
	 * Get the currently set HTTP status code.
	 *
	 * @return int HTTP status code
	 **/
	public function getStatusCode(){
		return $this->statusCode;
	}

	/**
	 * Get the message associated with the given HTTP status code.
	 *
	 * @param int $statusCode HTTP status code
	 * @return string The associated message
	 */
	protected function getStatusCodeMessage($statusCode) {
		return (isset(self::$HTTPStatusCodes[$statusCode])) ? self::$HTTPStatusCodes[$statusCode] : '';
	}

	/**
	 * Set the HTTP response type.
	 *
	 * @return void
	 * @throws InvalidArgumentException on an unsupported response type.
	 **/
	public function setResponseType($type){
		if(!in_array($type, self::$supportedResponseTypes)){
			throw new InvalidArgumentException('Invalid Response Type.');
		}
		$this->responseType = $type;
	}

	/**
	 * Get the currently set HTTP response type.
	 *
	 * @return void
	 **/
	public function getResponseType(){
		return in_array($this->responseType, self::$supportedResponseTypes) ? $this->responseType : 'text/html';
	}

	/**
	 * Set the response body, to be output verbatim.
	 *
	 * @return void
	 **/
	public function setBody($body){
		$this->body = $body;
	}

	/**
	 * Set the URI of the location header.
	 *
	 * @param string $URI Location.
	 * @return void
	 */
	public function setLocation($URI){
		$this->location = $URI;
	}

	/**
	 * Add headers to be sent with response
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function setAdditionalHeader($key,$value) {
			$this->additionalHeaders[$key] = $value;
	}

	/**
	 * Shortcut for adding multiple headers
	 *
	 * @param array $headers
	 */
	public function setAdditionalHeaders($additionalHeaders) {
		foreach ($additionalHeaders as $key => $value) {
			$this->setAdditionalHeader($key,$value);
		}
	}

	/**
	 * Wrapper for the header function
	 *
	 * @param string $string
	 * @param string $replace
	 * @param int $http_response_code
	 */
	protected function setHeader($string, $replace = NULL, $http_response_code = NULL){
		return header($string, $replace, $http_response_code);
	}

	protected function getStatusHeader() {
		return 'HTTP/1.1 ' . $this->statusCode . ' ' . $this->getStatusCodeMessage($this->statusCode);
	}

	/**
	 * Shortcut wrapper for OK
	 */
	public function ok($body) {
		$this->setStatusCode(200);
		$this->setBody($body);
	}


	/**
	 * Shortcut wrapper for redirect
	 */
	public function redirect($uri){
		$this->setLocation($uri);
		$this->setStatusCode(301);
	}


	/**
	 * Shortcut wrapper for not found
	 */
	public function notFound($message) {
		$this->setStatusCode(404);
		$this->setBody($message);
	}


	/**
	 * Shortcut wrapper for error
	 */
	public function error($message) {
		$this->setStatusCode(500);
		$this->setBody($message);
	}

	/**
	 * Send the response.
	 *
	 * @return void
	 */
	public function send(){

		$this->setHeader($this->getStatusHeader());
		$this->setHeader('Content-type: ' . $this->getResponseType());

		foreach($this->additionalHeaders as $key=>$value) {
			$this->setHeader($key.': '.$value);
		}
		if(!is_null($this->location)){
			$this->setHeader("Location: $this->location");
		}

		// If we have a body, return it.
		if(!empty($this->body)) {
			echo $this->body;
		}
	}
}
