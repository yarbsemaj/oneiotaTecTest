<?php

namespace app\mapper;

use core\mapper\AbstractMapper;

class ProductMapper extends AbstractMapper
{
	protected $source = 'products';

	public function findBySKU($sku)
	{
		$items = $this->all();

		foreach ($items as $item) {
			if ($item->SKU == $sku) {
				return $item;
			}
		}
	}

    public function findByID($sku)
    {
        $items = $this->all();

        foreach ($items as $item) {
            if ($item->id == $sku) {
                return $item;
            }
        }
    }
}