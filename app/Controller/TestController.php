<?php

declare(strict_types=1);

namespace App\Controller;

use PDO;
use App\ViewRenderer;
use Dotenv\Validator;
use App\Http\Response;
use App\Validation\Validators;
use App\Http\ResponseInterface;
use App\Contracts\AuthInterface;
use App\Factory\ResponseFactory;
use App\Http\ServerRequestInterface;
use App\DataObjects\RegisterUserData;
use Dotenv\Exception\ValidationException;
use App\Contracts\RequestFactoryValidatorInterface;
use App\Contracts\RequestValidatorFactoryInterface;
use App\RequestValidators\LoginUserRequestValidator;
use App\RequestValidators\RegisterUserRequestValidator;
use App\Exceptions\ValidationException as ExceptionsValidationException;

class TestController
{


	public function __construct(
		private ViewRenderer $view,
		private PDO $pdo,
		private readonly AuthInterface $auth,
		private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
	) {}


	public static function testing(): string
	{
		return "This is DiContainer testing";
	}



	public  function index(): ResponseInterface
	{
		$html = $this->view->render('index_view.php');
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

		$data = $this->requestValidatorFactory->make(RegisterUserRequestValidator::class)->validate($request->getParsedBody());

		$this->auth->register(new RegisterUserData($data['name'], $data['email'], $data['password']));

		return $response->redirect(BASE_PATH . '/');
	}


	public function loginView(): ResponseInterface
	{
		$html = $this->view->render('auth/login_view.php');
		return (new Response())->write($html);
	}


	public function login(ServerRequestInterface $request, ResponseInterface $response)
	{


		$data = $this->requestValidatorFactory->make(LoginUserRequestValidator::class)->validate($request->getParsedBody());


		if (!$this->auth->attemptLogin($data)) {
			throw new ExceptionsValidationException(['password' => ['You have entered invalid username or password']]);
		}

		return $response->withHeader('Location', BASE_PATH . '/')->withStatus(302);
	}

	public function logout(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
	{

		$this->auth->logout();

		return $response->redirect(BASE_PATH . '/');
	}


	public function test()
	{
		echo "hello from test method";
	}
}
