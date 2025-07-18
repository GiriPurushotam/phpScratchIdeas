<?php

declare(strict_types=1);

namespace App\Controller;

use App\Http\ResponseInterface;
use App\Http\ServerRequestInterface;
use App\Validation\Validators;
use App\ViewRenderer;
use PDO;

class TestController
{


	public function __construct(private ViewRenderer $view, private PDO $pdo) {}


	public static function testing(): string
	{
		return "This is DiContainer testing";
	}



	public  function index(): string
	{
		return $this->view->render('index_view.html');
	}

	public function json(): array
	{
		return  [
			'status' => 'sucess',
			'message' => 'this is a JSON response from TestController'
		];
	}



	public function registerView(): string
	{
		return $this->view->render('auth/register_view.html');
	}



	public function register(ServerRequestInterface $request, ResponseInterface $response): void
	{

		$data = $request->getParsedBody();
		$v = new Validators($data);

		$v->rule('required', ['name', 'email', 'password', 'confirmPassword']);
		$v->rule('email', 'email');
		$v->rule('equals', 'confirmPassword', 'password');

		$v->rule(function ($field, $value, $params, $fields) {
			$stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
			$stmt->execute(['email' => $value]);
			return $stmt->fetchColumn() == 0;
		}, 'email');

		if (!$v->validate()) {
			$response->withJson(['errors' => $v->errors()], 422);
			return;
		}

		try {
			$stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
			$stmt->execute([
				'name' => $data['name'],
				'email' => $data['email'],
				'password' => $data['password'],
			]);

			$response->redirect(BASE_PATH . '/');
		} catch (\Exception $e) {
			// $response->withJson(["error" => 'Something went wrong: ' . $e->getMessage()], 500);
		}
	}


	public function loginView(): string
	{
		return $this->view->render('auth/login_view.html');
	}



	public function login() {}


	public function test()
	{
		echo "hello from test method";
	}
}
