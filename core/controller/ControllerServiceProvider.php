<?php

namespace core\controller;

use core\App;
use core\services\ServiceProvider;
use core\Request;

class ControllerServiceProvider implements ServiceProvider
{
	public function registerServices(App $app) {

		$app->register('ControllerLoader', function($app) {
			return new ControllerLoader($app);
		});
	}
}