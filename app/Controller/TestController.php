<?php

declare(strict_types=1);

namespace App\Controller;

use App\ViewRenderer;

class TestController {
	public function __construct(private ViewRenderer $view)
	{
		
	}
	public static function testing(): string {
		return "This is DiContainer testing";
	}

	public  function index(): string {
		return $this->view->render('index_view.html');
	}

	public function json():array {
		return  [
			'status' => 'sucess',
			'message' => 'this is a JSON response from TestController'
		];
	}
}