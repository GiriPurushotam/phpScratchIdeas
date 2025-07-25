<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\ResponseFactory;
use App\Http\Response;
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



	public  function index(): ResponseInterface
	{
		$html = $this->view->render('index_view.html');
		return (new Response())->write($html);
	}

	public function json(): array
	{
		return  [
			'status' => 'sucess',
			'message' => 'this is a JSON response from TestController'
		];
	}



	public function registerView(): ResponseInterface
	{
		$html = $this->view->render('auth/register_view.html');
		return (new Response())->write($html);
	}



	public function register(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
	{

		$data = $request->getParsedBody();
		$v = new Validators($data);

		$v->label('confirmPassword', 'Confirm Password');
		$v->label('password', 'password');

		$v->rule('required', ['name', 'email', 'password', 'confirmPassword']);
		$v->rule('email', 'email');
		$v->rule('equals', 'confirmPassword', 'password');

		$v->rule(function ($field, $value, $params, $all) use ($v) {
			$stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
			$stmt->execute(['email' => $value]);

			if ($stmt->fetchColumn()) {
				$v->addCustomError($field, "Email Address is already taken");
				return false;
			}
			return true;
		}, 'email');


		if (!$v->validate()) {
			return $response->write($this->view->render(
				'auth/register_view.html',
				[
					'errors' => $v->errors(),
					'old' => $data,
				]
			))->withStatus(422);


			// return $response->withJson(['errors' => $v->errors()], 422);
		}

		$hashPassword = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);

		try {
			$stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
			$stmt->execute([
				'name' => $data['name'],
				'email' => $data['email'],
				'password' => $hashPassword,
			]);

			$userId = (int) $this->pdo->lastInsertId();

			$_SESSION['user'] = [
				'id' => $userId,
				'name' => $data['name'],
				'email' => $data['email'],
			];

			session_regenerate_id(true);

			return $response->redirect(BASE_PATH . '/');
		} catch (\Exception $e) {
			throw new \Exception("Something wrong");
		}
	}


	public function loginView(): ResponseInterface
	{
		$html = $this->view->render('auth/login_view.html');
		return (new Response())->write($html);
	}



	public function login(ServerRequestInterface $request, ResponseInterface $response)
	{
		// $data = $request->getParsedBody();
		// $v = new Validators($data);

		// $v->rule('required', ['email', 'password']);
		// $v->rule('email', 'email');
	}


	public function test()
	{
		echo "hello from test method";
	}
}
