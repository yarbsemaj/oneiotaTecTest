<?php
namespace core\helper;

use InvalidArgumentException;

class Json
{

	const AS_ARRAY = true;

	/**
	 * Pass this a file and it will return you the decoded JSON.
	 *
	 * Be warned, THIS WILL THROW HARD ERRORS, so check the file exists before you use it
	 * otherwise you might be in for a nasty surprise. There might be a 'silent' version
	 * of this that turns up later, but I've written and unwritten that kind of code for
	 * this several times and always end up chucking it away.
	 *
	 * @param  string  $file
	 * @param  boolean $assoc
	 * @return stdClass
	 * @throws InvalidArgumentException
	 */
	public static function loadFile($file, $assoc = false) {
	
		// if not readable then throw out
		if (!is_readable($file)) {
			throw new InvalidArgumentException('Json file ' . $file . ' not readable');
		}

		$data = json_decode(file_get_contents($file), $assoc);

		// if no data or doesn't decode then throw out too
		if (!$data) {
			throw new InvalidArgumentException('Json file ' . $file . ' either has no data' .
				' or could not be decoded');
		}

		return $data;
	}

	/**
	* Will do a simple detection to see if it's JSON or not
	*
	* @param  string $str some string of body content probably
	* @return bool
	* @author Iain Kydd
	*/
	public static function detect($str) {

	  json_decode($str);
	  return json_last_error() == JSON_ERROR_NONE;

	  // potentially error prone but likely quicker method
	  // $firstChar = substr($str, 0, 1);
	  // return $firstChar == '{' || $firstChar == '[';
	}
}
