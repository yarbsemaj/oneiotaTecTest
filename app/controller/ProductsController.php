<?php

namespace app\controller;

use core\Response;

class ProductsController extends AppController
{
	public function all()
	{
		$mapper = $this->mappers->load('Product');

		$products = $mapper->all();

		$content = $this->view->render('products/list', array('products' => $products));
		$content = $this->standardPage($content);

		$response = new Response();
		$response->ok($content);

		return $response;
	}

    public function quickView()
    {
        $mapper = $this->mappers->load('Product');

        $product = $mapper->findByID($this->request->id);

        $content = $this->view->render('products/quickView/fragment', array('product' => $product));

        $response = new Response();
        $response->ok($content);

        return $response;
    }
}
