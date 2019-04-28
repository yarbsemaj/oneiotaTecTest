<?php

namespace core\controller;

use core\Request;
use core\App;
use InvalidArgumentException;

/**
 * The controllers have a fair few dependencies and may need to be instantiated on request
 * (i.e. we might not know which controller we want until mid-way through the class). So instead
 * of trying to inject the controller itself we inject this loader, which contains all the knowledge
 * about how to load them and keeps it all tidied away.
 */
class ControllerLoader {

	protected $app;

	/**
	 * When this is created (by a service proivder) then it will need the $app to be able to make other
	 * dependencies, and we're just going to store this for later
	 *
	 * @param App $app
	 */
	public function __construct(App $app) {
		$this->app = $app;
	}

	/**
	 * Main method for loading the controller class name. Just needs the first part, capitalised
	 * e.g. $loader->load('Products') will load app\controller\ProductsController
	 *
	 * @param  string $name
	 * @return core\controller\AbstractController
	 * @throws InvalidArgumentException
	 */
	public function load($name){
		// upper case the name to match the class
		$controllerName = ucfirst($name) . 'Controller';
		$class = "\\app\\controller\\$controllerName";

		// check it exists
		if(!class_exists($class,TRUE) || !is_subclass_of($class, '\core\controller\AbstractController')){
			throw new InvalidArgumentException("Class '$class' does not exist or is not a child of AbstractController.");
		}

		return new $class($this->app, $this, $this->app->make('MapperLoader'),
			$this->app->make('Configuration'), $this->app->make('View'), $this->app->request);
	}
}
