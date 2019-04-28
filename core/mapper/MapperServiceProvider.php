<?php

namespace core\mapper;

use core\App;
use core\services\ServiceProvider;

class MapperServiceProvider implements ServiceProvider {

	public function registerServices(App $app) {

		$app->register('MapperLoader', function($app) {
			return new MapperLoader($app);
		});
	}
}