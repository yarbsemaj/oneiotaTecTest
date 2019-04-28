<?php

namespace core;

use core\services\Services;
use core\services\ServiceProvider;
use core\services\ServiceContainer;
use InvalidArgumentException;

/**
 * One class to rule them all....
 *
 * This is the app class which basically takes over from index.php in terms
 * of bootstrapping duties etc.
 */

class App implements ServiceContainer {

	protected $config;
	protected $services;
	protected $router;
	public $request;

	/**
	 * Set the constants as soon as we can, just in case
	 */
	public function __construct() {
		$this->setConstants();
	}

	/**
	 * launch is the main trigger function and gets called in index.php to
	 * kick this whole thing off
	 */
	public function launch() {

		// create a Request we can use
		$this->request = new Request();

		// setup phase
		$this->setup();

		// render phase
		$this->render($this->request);
	}

	/**
	 * The setup phase, where we load various bits. We
	 * initialise the service providers and some configuration
	 */
	protected function setup() {

		$this->loadServices();

		$this->loadConfiguration();
	}

	/**
	 * The render phase, we're we create the Router and the dispatch
	 * the Request
	 *
	 * @param  Request $request
	 */
	protected function render(Request $request) {

		$this->loadRouter();

		$this->renderRequest($request);
	}

	/**
	 * This is just so that the services can be accessed outside the app
	 *
	 * @return mixed
	 */
	public function make($key) {
		return call_user_func_array(array($this->services, 'make'), func_get_args());
	}

	/**
	 * This is just so that the services can be registered outside the app
	 *
	 * @return mixed
	 */
	public function register($key, $callback) {
		return call_user_func(array($this->services, 'register'), $key, $callback);
	}

	/**
	 * This is just so that the services can be registered outside the app
	 *
	 * @return mixed
	 */
	public function singleton($key, $callback) {
		return call_user_func(array($this->services, 'singleton'), $key, $callback);
	}

	/**
	 * Various constants may need to be set here. They're set in the constructor
	 * to avoid them being set twice and causing errors.
	 */
	protected function setConstants() {
		// Define the local config path
		define('LOCAL_CONFIG_PATH', ROOT_PATH . '/config/');
		define('LOCAL_TEMPLATE_PATH', ROOT_PATH . '/app/views/');
	}

	/**
	 * This boots up the configuration class and sets on a couple
	 * other objects for debugging/display purposes.
	 */
	protected function loadConfiguration() {
		// get the configuration and set it on the error handler
		// and debugger
		$this->config = $this->make('Configuration');
	}

	/**
	 * This guy reads the serviceProvider file, gets the list of providers,
	 * iterates over it and registers all the services that they have. This
	 * needs to happen very early on as lots of the other classes will need
	 * to use the services to be instantiated, even if they are really core
	 * classes like configuration (thoguh at time of writing that did not
	 * yet get instantiated through services)
	 *
	 * @throws InvalidArgumentException
	 */
	protected function loadServices() {

		$this->services = new Services($this);

		$serviceProviders = require LOCAL_CONFIG_PATH . 'serviceProviders.php';

		// Load procedural helpers
		foreach ($serviceProviders as $provider) {
			if (!class_exists($provider)) {
				throw new InvalidArgumentException('Service provider ' . $provider
					. ' either does not exist or cannot be loaded for some reason');
			}
			$serviceProvider = new $provider();

			if (!($serviceProvider instanceof ServiceProvider)){
				throw new InvalidArgumentException('Service provider ' . $provider
					. ' is not an instance of ServiceProvider');
			}
			$serviceProvider->registerServices($this, $this->services);
		}
	}

	/**
	 * Simple load to get us a Router. Not much going on in here.
	 */
	protected function loadRouter() {
		$this->router = $this->services->make('Router');
	}

	/**
	 * Here we actually render the request out, dispatch it to the controller
	 * etc.
	 *
	 * @param  Request $request
	 */
	protected function renderRequest(Request $request) {

		$response = $this->router->dispatch($request);

		$response->send();
	}
}