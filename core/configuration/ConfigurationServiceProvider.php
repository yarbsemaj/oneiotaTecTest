<?php

namespace core\configuration;

use core\App;
use core\services\ServiceProvider;
use core\Request;
use core\helper\Json;

class ConfigurationServiceProvider implements ServiceProvider
{
	public function registerServices(App $app) {

		$app->singleton('Configuration', function($app){

			$config = Json::loadFile(LOCAL_CONFIG_PATH . 'config.json');

			return new Configuration($config);
		});
	}
}