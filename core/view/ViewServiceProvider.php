<?php

namespace core\view;

use core\App;
use core\services\ServiceProvider;

class ViewServiceProvider implements ServiceProvider
{
	public function registerServices(App $app) {

		$app->register('View', function($app) {
			return new View(LOCAL_TEMPLATE_PATH);
		});
	}
}