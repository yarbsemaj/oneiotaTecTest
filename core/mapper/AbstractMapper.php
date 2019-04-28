<?php
namespace core\mapper;

use core\helper\Json;

/**
 * Mock up some kind of pseudo datasource here. Normally this would be a DB
 * or API call, but for simplicity here we'll just pull the data from JSON
 * text files.
 */
abstract class AbstractMapper {

	protected $folder;
	/**
	 * Should be stated on the child classes to say which file contains their
	 * data. Should omit '.json' form the end as this will be added automatically
	 * @var string
	 */
	protected $source;

	/**
	 * Just set the main folder for all the files here
	 * @param string
	 */
	public function __construct($folder){
		$this->folder = $folder;
	}

	/**
	 * Tidied away method for getting the finished file path
	 *
	 * @return string
	 */
	protected function getFilePath()
	{
		return $this->folder . '/' . $this->source . '.json';
	}

	/**
	 * Load all the data
	 * @return array
	 */
	public function all()
	{
		$path = $this->getFilePath();

		$json = Json::loadFile($path);

		// the data property contains the array, leaving room for other properties
		return $json->data;
	}

	/**
	 * Find one by its ID. Obviously not a very efficient process here as normally
	 * you'd just be pulling one from the DB/API, but this just needs to be simple.
	 *
	 * @param  int $id
	 * @return object|null
	 */
	public function find($id)
	{
		$items = $this->all();

		foreach ($items as $item) {
			if ($item->id == $id) {
				return $item;
			}
		}
	}
}
