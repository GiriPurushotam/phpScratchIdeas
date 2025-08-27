<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contracts\AuthInterface;
use App\Http\Response;
use App\Http\ResponseInterface;
use App\Http\ServerRequestInterface;
use App\ViewRenderer;

class CategoriesController
{

    public function __construct(
        private readonly ViewRenderer $view,
        private readonly AuthInterface $auth
    ) {}

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $html = $this->view->render('/categories/categories_index.php');
        return (new Response())->write($html);
    }


    public function store(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $response->withHeader('Location', BASE_PATH . '/categories')->withStatus(302);
    }


    public function delete(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $response;
    }
}
