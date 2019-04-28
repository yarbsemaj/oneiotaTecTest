<?php
namespace core;
/**
 * Autoloader responsible for finding requested classes.
 *
 **/
class AutoLoader {
	
	/**
	 * The root file path from which to load classes.
	 *
	 * @var string
	 */
	protected $rootPath;


	/**
	 * Constructor
	 *
	 * @param string $rootPath The root file path from which to load classes.
	 */
	public function __construct($rootPath){
		$this->rootPath = $rootPath;
	}
	
	/**
	 * Attempts to load the specified class.
	 *
	 * @return void
	 * @throws Exception If class not found.
	 **/
	public function autoLoad($name){
		$path = NULL;
		$namespaceParts = explode('\\', $name);
		$pathTail = implode(DIRECTORY_SEPARATOR, $namespaceParts) . '.php';
		
		$tryPath = $this->rootPath . '/' . $pathTail;
		
		if(is_readable($tryPath)){
			$path = $tryPath;
		}
		
		// this must not throw an exception or do any kind of error as if it does
		// then when you use class_exists it will error instead of returning false
		if ($path) {
			require_once($path); 
		}
	}
	
}
