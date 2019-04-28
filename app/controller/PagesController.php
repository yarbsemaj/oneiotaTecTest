<?php

namespace app\controller;

use core\Response;

class PagesController extends AppController
{
	protected function getPage($slug)
	{
		$mapper = $this->mappers->load('Page');

		$page = $mapper->findBySlug($slug);

		$content = $this->view->render('pages/show', array('page' => $page));
		$content = $this->standardPage($content);

		$response = new Response();
		$response->ok($content);

		return $response;
	}

	public function faqs()
	{
		return $this->getPage('faqs');
	}

	public function delivery()
	{
		return $this->getPage('delivery');
	}

	public function tshirts()
	{
		return $this->getPage('t-shirts-and-vests');
	}

	public function trainers()
	{
		return $this->getPage('trainers-and-boots');
	}

	public function offers()
	{
		return $this->getPage('special-offers');
	}
}
