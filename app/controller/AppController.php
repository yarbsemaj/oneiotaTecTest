<?php

namespace app\controller;

use core\controller\AbstractController;
use core\view\View;
use core\Response;
use core\JsonResponse;

abstract class AppController extends AbstractController
{
	public function standardPage($content = '')
	{
		$header = $this->view->render('common/header');
		$footer = $this->view->render('common/footer');

		return $this->view->render('common/frame', array(
			'header' => $header,
			'content' => $content,
			'footer' => $footer
		));
	}
}
