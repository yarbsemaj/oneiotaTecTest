<?php
namespace core;

/**
 * Represents a request coming in
 **/
class Request {

	protected $params;
	protected $data;

	/**
	 * At the beginning we'll just sort out the various inputs
	 */
	public function __construct(){
		$this->initialiseParams();
	}

	/**
	 * Alias for file_get_contents('php://input')
	 *
	 * @return string Content of php://input
	 **/
	private function getInputStream($returnArray = false){
		return file_get_contents('php://input');
	}

	/**
	 * Sort our incoming data out
	 */
	private function initialiseParams(){

		$this->params = $_REQUEST;
		switch ($this->getMethod()) {
			case 'PUT':
			case 'POST':
				$this->data = $this->getInputStream();
				break;
		}
	}

	/**
	 * Simple getter for all the params
	 * @return array
	 */
	public function getParams(){
		return $this->params;
	}

	/**
	 * Simple getter to return the retreived raw data
	 * @return string
	 */
	public function getData(){
		return $this->data;
	}

	/**
	 * Wrapper to get the JSON data sent
	 * @return mixed
	 */
	public function getJson() {
		return json_decode($this->getData());
	}

	/**
	 * Allows for convenient use of $request->someParam
	 *
	 * @param  string $key
	 * @return mixed
	 */
	public function __get($key) {
		return isset($this->params[$key]) ? $this->params[$key] : NULL;
	}

	/**
	 * Overloaded to allow for null properties.
	 */
	public function __isset($key) {
		return array_key_exists($key, $this->params);
	}

	/**
	 * Get the request verb
	 *
	 * @return string - The request verb (GET, POST, PUT or DELETE).
	 **/
	public function getMethod(){
		return strtoupper($_SERVER['REQUEST_METHOD']);
	}

	/**
	 * Returns specified HTTP headers
	 *
	 * @param  string $header
	 * @return string|null
	 */
	public function getHeader($header) {
		$header = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
		return isset($_SERVER[$header]) ? $_SERVER[$header] : null;
	}

	/**
	 * Get the full current URL here.
	 *
	 * @return string
	 */
	public function getURL() {

		$protocol = $this->getProtocol();
		$serverName = $this->getHost();
		$port = $this->getPort();

		return $protocol . '://' . $serverName . ($port ? ':' . $port : '') . $this->getRequestUri();
	}

	/**
	 * Return if this request is via HTTPS or not.
	 *
	 * @return bool
	 */
	public function isHttps() {
		return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
	}

	/**
	 * Get the full request URI from the server data.
	 *
	 * @return string
	 */
	public function getRequestUri() {
		return isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : false;
	}

	/**
	 * Get the raw query string from the server data if the params themselves are
	 * not adequate
	 *
	 * @return string
	 */
	public function getQueryString() {
		return isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : false;
	}

	/**
	 * Get the path of the request
	 *
	 * @return string
	 */
	public function getPath() {
		return strtok($this->getRequestUri(), '?');
	}

	/**
	 * Get the request protocol (http or https)
	 *
	 * @return string
	 */
	public function getProtocol() {
		return $this->isHttps() ? 'https' : 'http';
	}

	/**
	 * Returns the hostname.
	 *
	 * @return string
	 */
	public function getHost() {
		return (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $this->getHeader('Host');
	}

	/**
	 * Returns the port of the current request if it's anything other than the default '80'
	 *
	 * @return string|boolean
	 */
	public function getPort() {
		$port = $_SERVER["SERVER_PORT"];
		return ($port != "80" && (!$this->isHttps() || $port != '443')) ? $port : false;
	}

	/**
	 * Get the IP of the request.
	 *
	 * @return string - IP address.
	 **/
	public function getIP(){
		return $_SERVER['REMOTE_ADDR'];
	}

}
