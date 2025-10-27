<?php

declare(strict_types=1);

namespace App\Controller;

use App\ViewRenderer;
use App\Http\Response;
use App\Http\ResponseInterface;
use App\Contracts\AuthInterface;
use App\Services\CategoryService;
use App\Formatter\ResponseFormatter;
use App\Http\ServerRequestInterface;
use App\Contracts\RequestValidatorFactoryInterface;
use App\RequestValidators\CreateCategoryRequestValidator;
use App\RequestValidators\UpdateCategoryRequestValidator;

class CategoriesController
{

    public function __construct(
        private readonly ViewRenderer $view,
        private readonly AuthInterface $auth,
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly CategoryService $categoryService,
        private readonly ResponseFormatter $responseFormatter
    ) {}

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user = $request->getAttribute('user');
        $html = $this->view->render('/categories/categories_index.php', [
            'categories' => $this->categoryService->getAll($user->getId()),
        ]);
        return (new Response())->write($html);
    }


    public function store(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data =  $this->requestValidatorFactory->make(CreateCategoryRequestValidator::class)->validate($request->getParsedBody());

        $this->categoryService->create($data['name'], $request->getAttribute('user'));


        return $response->withHeader('Location', BASE_PATH . '/categories')->withStatus(302);
    }


    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->categoryService->delete((int) $args['id']);

        return $response->withHeader('Location', BASE_PATH . '/categories')->withStatus(302);
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {



        $category = $this->categoryService->getById((int) $args['id']);

        if (! $category) {
            return $response->withStatus(404);
        }

        $data = ['id' => $category->getId(), 'name' => $category->getName()];

        return $this->responseFormatter->asJson($response, $data);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $data =  $this->requestValidatorFactory->make(UpdateCategoryRequestValidator::class)->validate($args + $request->getParsedBody());

        $category = $this->categoryService->getById((int) $data['id']);

        if (! $category) {
            return $response->withStatus(404);
        }

        $this->categoryService->update($category, $data['name']);


        return $response;
    }
}
