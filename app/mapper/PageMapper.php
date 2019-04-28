<?php

namespace app\mapper;

use core\mapper\AbstractMapper;

class PageMapper extends AbstractMapper
{
	protected $source = 'pages';

	public function findBySlug($slug)
	{
		$items = $this->all();

		foreach ($items as $item) {
			if ($item->slug == $slug) {
				return $item;
			}
		}
	}
}