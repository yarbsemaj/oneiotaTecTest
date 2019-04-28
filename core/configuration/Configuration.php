<?php
namespace core\configuration;

/**
 * Represents a configuration setting data structure.
 *
 * Allows for dot notation when querying nested variables
 * $config->get('FOO.BAR.CAT.DOG') is equivalent to $config->FOO->BAR->CAT->DOG;
 * Keys are case-sensitive.
 *
 **/
class Configuration {

	protected $config;

	/**
	 * @param stdClass $config
	 */
	public function __construct($config) {
		$this->config = $config;
	}

	/**
	 * This will accept a key and also sub-keys with JS dot notation.
	 * i.e. if FOO is an object $config->has('FOO.BAR') will check
	 * whether FOO has a property BAR
	 *
	 * @param  string  $key
	 * @return boolean
	 */
	public function has($key){

		$base = strtok($key, '.');

		// if we don't have the base then just throw it out
		// straight away
		if (!isset($this->config->$base)) {
			return false;
		}

		$base = $this->config->$base;

		// loop over the string, taking off the next piece of it
		// up to the next dot and adding that property to our variable
		while ($prop = strtok('.')) {
			if (!isset($base->$prop)) {
				return false;
			} else {
				$base = $base->$prop;
			}
		}
		return true;
	}

	/**
	 * This will accept a key and also sub-keys with JS dot notation.
	 * i.e. if FOO is an object $config->has('FOO.BAR') will return
	 * the value of $config->FOO->BAR;
	 *
	 * @param  string  $key
	 * @return boolean
	 */
	public function get($key, $default = null) {

		if (!$this->has($key)) {
			return $default;
		}

		$base = strtok($key, '.');

		$base = $this->config->$base;

		// loop over the string, taking off the next piece of it
		// up to the next dot and adding that property to our variable
		while ($prop = strtok('.')) {
			$base = $base->$prop;
		}
		// we need to clone the value if it's an object to make sure it's not passed by reference
		// if we don't then we run the risk of setting data on the config variable accidentally when
		// we ask for an object then start modifying it (e.g. default seo data)
		if (is_object($base)){
			$base = clone $base;
		}
		return $base;
	}
}
