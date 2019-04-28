<?php
namespace core\routing;

use core\controller\ControllerLoader;
use core\Request;
use core\Response;

/**
 * Routes HTTP requests to actions
 *
 **/
class Router {

	protected $routes = array();

	/**
	 * @param ControllerLoader $controllers;
	 * @param array $routes
	 */
	public function __construct(ControllerLoader $controllers, $routes = array()){
		$this->controllers = $controllers;
		$this->routes = $this->loadRoutes($routes);
	}

	/**
	 * Uses the routes and creates an array of fully formed Route objects for use later.
	 * They are ordered so as to avoid some conflicts
	 *
	 * @return array - an array of Route classes
	 */
	protected function loadRoutes($raw){

		$routes = array();
		foreach($raw as $route) {
			$routes[] = new Route($route->pattern, (array) $route->methods, $route->controller);
		}

		return $routes;
	}

	/**
	 * This just gets the loaded routes and iterates over them until it finds a match.
	 * It also stores a found Route in a static variable so it can be accessed later
	 *
	 * @param  string $path
	 * @return mixed - can be a Route or can be void
	 */
	protected function findRoute($path, $method) {

		$path = trim($path, '/');

		$routes = $this->routes;
		foreach($routes as $route) {
			if($route->doesMatch($path, $method)) {
				return $route;
			}
		}
	}

	/**
	 * Takes a URI string and returns a not found for it (using the URL from config)
	 * @param  string $URI
	 * @return Response
	 */
	protected function notFoundResponse($URI) {

		$response = new Response();
		$response->notFound('Could not find that page');

		return $response;
	}

	/**
	 * There is where the loading and running of the business logic actually happens
	 *
	 * @param  Route    $route    the matched Route
	 * @param  Request  $request
	 * @param  Response $response
	 * @return Response
	 */
	protected function runController(Route $route, Request $request) {

		$controllerName = $route->getController();
		$methodName = $route->getMethod($request->getMethod());

		$controller = $this->controllers->load($controllerName);

		if(!is_callable(array($controller, $methodName))){
			throw new \Exception("Could not call action method $methodName on class $controllerName.");
		}

		return call_user_func_array(array($controller, $methodName), array());
	}

	/**
	 * This will take a route (or lack of) and return some kind of request (eg not supported or not found redirect
	 * or ideally a processed Route from a controlller)
	 *
	 * @param  Request  $request
	 * @return Response - a Response of some type should get returned whatever happens (unless some kind of
	 * exception is thrown)
	 */
	protected function getResponse(Request $request, Route $route = null) {

		// if we can find a route then do the business...
		if ($route) {

			$response = $this->runController($route, $request);

			// currently this is for graceful fallback as the old method does not return anything
			// so we need to cater for those functions too by checking to see if something is returned
			if (!($response instanceof Response)) {
				throw new \Exception('Invalid response, no Response class returned');
			}

			return $response;

		// ... but if not then we need to do some kind of 404
		} else {

			$path = $request->getPath();

			return $this->notFoundResponse($path);
		}
	}

	/**
	 * Maps the Request to an action and calls it
	 *
	 * @param  Request $request
	 * @return Response - the finished response ready for sending
	 **/
	public function dispatch(Request $request){

		$route = $this->findRoute($request->getPath(), $request->getMethod());

		return $this->getResponse($request, $route);
	}

}
