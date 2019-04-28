<?php
namespace core\routing;

/**
 * Represents an API route.
 *
 **/
class Route {

	protected $controllerName;
	protected $methods;
	protected $pattern;

	/**
	 * Constructor
	 *
	 * @param string $pattern
	 * @param array $methods
	 * @param string $controllerName
	 */
	public function __construct($pattern, $methods = NULL, $controllerName = NULL) {
		$this->controllerName = $controllerName;
		$this->pattern = $pattern;
		$this->methods = $methods;
	}

	/**
	 * Simple getter for the pattern
	 * @return string
	 */
	public function getPattern() {
		return $this->pattern;
	}

	/**
	 * Simple getter for the methods array
	 * @return array
	 */
	public function getMethods() {
		return $this->methods;
	}

	/**
	 * Tests if the given path matches the route.
	 *
	 * @param string $path Path string.
	 * @return boolean TRUE if path matches route, FALSE otherwise.
	 */
	public function doesMatch($path, $method = 'GET'){
		return $path == $this->pattern && array_key_exists($method, $this->methods);
	}


	/**
	 * Get the name of the controller associated with this route.
	 *
	 * @return string Name of the controller associated with the route.
	 */
	public function getController(){
		return $this->controllerName;
	}

	/**
	 * Returns true if the given HTTP verb is supported for this route.
	 *
	 * @param string $verb HTTP verb (DELETE, GET, HEAD, OPTIONS, POST, PUT)
	 * @return boolean TRUE if the verb is supported, FALSE otherwise.
	 */
	public function supportsVerb($verb){
		return isset($this->methods[$verb]);
	}

	/**
	 * Get the list of supported HTTP verbs for this route.
	 *
	 * @return array Array of supported HTTP verbs.
	 */
	public function getSupportedVerbs(){
		$verbs = array();
		foreach($this->methods as $verb => $method) {
			if(!is_null($method)){
				$verbs[] = $verb;
			}
		}
		return $verbs;
	}

	/**
	 * Get the method name associated with the given HTTP verb.
	 * The method should be called on the controller in response to an
	 * HTTP request with the given verb.
	 *
	 * @param string $verb
	 * @return void
	 */
	public function getMethod($verb){
		return $this->methods[strtoupper($verb)];
	}
}
