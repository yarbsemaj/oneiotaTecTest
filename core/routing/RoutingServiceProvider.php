<?php

namespace core\routing;

use core\App;
use core\services\Services;
use core\services\ServiceProvider;
use core\Request;
use InvalidArgumentException;
use core\helper\Json;

class RoutingServiceProvider implements ServiceProvider
{
	public function registerServices(App $app) {

		$app->register('Router', function($app){

			$config = $app->make('Configuration');

			$routes = Json::loadFile(LOCAL_CONFIG_PATH . $config ->get('ROUTES_FILE'));

			return new Router($app->make('ControllerLoader'), $routes);
		});
	}
}