<?php

namespace core\mapper;

use core\App;
use InvalidArgumentException;

/**
 * Mapper may be needed at various times and it's not fixed which one we will ned
 * so rather than trying to inject the correct mapper into each class we'll inject
 * a loader that they can use.
 */
class MapperLoader {

	protected $app;

	/**
	 * Store the app so that we can use it later as we may need to inject various
	 * bits and bobs into the loader
	 *
	 * @param App $app
	 */
	public function __construct(App $app) {
		$this->app = $app;
	}

	/**
	 * Load a mapper like this $loader->load('Product') - which will load app\mapper\ProductMapper
	 *
	 * @param  string $name
	 * @return core\mapper\AbstractMapper
	 * @throws InvalidArgumentException
	 */
	public function load($name){
		// upper case the name to match the class
		$mapperName = $name.'Mapper';
		$class = "\\app\\mapper\\$mapperName";

		// check it exists
		if(!class_exists($class,TRUE) || !is_subclass_of($class, '\core\mapper\AbstractMapper')){
			throw new InvalidArgumentException("Class '$class' does not exist or is not a child of AbstractMapper.");
		}

		$config = $this->app->make('Configuration');

		// currently the only this it needs is the path to the folder where all the data lives
		$folder = ROOT_PATH . $config->get('DATA_FOLDER');

		return new $class($folder);
	}
}