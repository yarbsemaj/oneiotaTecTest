<?php

namespace core\services;

use core\App;
use InvalidArgumentException;

/**
 * this class will house all the service provider callbacks, all the functions
 * that are used to construct the various classes. This is a key part of making
 * dependency injection work (which there is already a lot of) - it means that
 * yoiu don't have to remember how to instiate the classes each time e.g. create
 * an Input class and then put this into a Request class
 *
 * Why? This allows you to update constructors with new dependencies without having
 * to go and change them everywhere. It even allows you return different classes
 * without anyone knowing (e.g. child classes that have extra layers on them)
 *
 * Tt provides one central point for handling construction. Not everything will go through
 * here, just the more complex, dependant classes really
 *
 * This is not to be confused with a factory class, dynamic class names should not be used
 */
class Services implements ServiceContainer
{

	protected $callbacks = array();
	protected $singletons = array();
	protected $instances = array();

	public function __construct(App $app) {
		$this->app = $app;
	}

	/**
	 * this is used to add callback to the Service container
	 *
	 * @param  string $key - name which will be used when building an object e.g. 'Request' for new Request
	 * @param  closure $callback - this should be the function that creates the desired object. First arg of
	 * this callback will always be $services so that you can access the other callbacks inside
	 * any other args will just be appended after that
	 * @return null
	 */
	public function register($key, $callback) {
		$this->callbacks[$key] = $callback;
	}

	/**
	 * this is where the magic happens - the build request is made, the callback is fired
	 * and the args are sorted out. Out pops a newly formed object
	 *
	 * @param  string $key
	 * @return mixed - an instantiated object that depends on the service requested
	 */
	public function make($key) {

		// first check if it's a singleton and return that if one exists already
		if (in_array($key, $this->singletons) && isset($this->instances[$key]) && !is_null($this->instances[$key])){
			return $this->instances[$key];
		}
		// there could be any number of extra args here so we need them all
		$args = func_get_args();
		// get the key off the front (the name of the calss/callback we want)
		$key = array_shift($args);
		// stick the app on the very front
		array_unshift($args, $this->app);
		// check it exists and then call it
		if (array_key_exists($key, $this->callbacks)) {
			$callback = $this->callbacks[$key];
			$instance = call_user_func_array($callback, $args);
			// first check if it's a singleton and return that if one exists already
			if (in_array($key, $this->singletons)){
				$this->instances[$key] = $instance;
			}

			return $instance;
		}
		// throw a hard fail if it's broken so we know about - if this gets thrown then something's gone
		// really wrong
		else {
			throw new InvalidArgumentException('Invalid Service request made - cannot find this service: ' . $key);
		}
	}

	/**
	 * This guy will also register the callback, but it will mark it as a
	 * singleton i.e. the same instance will be returned every time, where as
	 * register will make a new instance every time
	 *
	 * @param  string $key
	 * @param  closure $callback
	 * @return null
	 */
	public function singleton($key, $callback) {
		$this->register($key, $callback);
		// add it to the list of singleton classes
		if (!in_array($key, $this->singletons)) {
			$this->singletons[] = $key;
		}
	}
}
