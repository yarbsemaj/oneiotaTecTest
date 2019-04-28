<?php
namespace core\controller;

use InvalidArgumentException;

use core\Response;
use core\JsonResponse;
use core\configuration\Configuration;
use core\Request;
use core\App;
use core\controller\ControllerLoader;
use core\mapper\MapperLoader;
use core\view\View;

abstract class AbstractController {

	protected $controllers;
	protected $mappers;
	protected $config;
	protected $request;
	protected $app;
	protected $view;

	/**
	 * Quite a few dependencies here, but all these will likely be useful
	 *
	 * @param App              $app         in case we need to use service providers to load something
	 *                                      else we can use $this->app->make('MyService')
	 * @param ControllerLoader $controllers we can use this to load more controllers if they have other
	 *                                      bits of content we might need e.g. $this->controllers->load('MyController')
	 * @param MapperLoader     $mappers     allows us to load mappers
	 * @param Configuration    $config      just in case we need to check config for any choices/data
	 * @param View             $view        so we can render views
	 * @param Request          $request     in case we need to inspect the request for data/params etc.
	 */
	public function __construct(App $app, ControllerLoader $controllers, MapperLoader $mappers,
		Configuration $config, View $view, Request $request){

		$this->app = $app;
		$this->controllers = $controllers;
		$this->mappers = $mappers;
		$this->config = $config;
		$this->view = $view;
		$this->request = $request;
	}
}
