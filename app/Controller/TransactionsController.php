<?php

declare(strict_types=1);

namespace App\Controller;

use App\Http\Response;
use App\Http\ResponseInterface;
use App\Http\ServerRequestInterface;
use App\Services\CategoryService;
use App\ViewRenderer;

class TransactionsController
{
    public function __construct(
        private readonly ViewRenderer $view,
        private readonly CategoryService $categoryService
    ) {}

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user = $request->getAttribute('user');
        $categories = $this->categoryService->getAllByUser($user);

        $html = $this->view->render('/transactions/transactions_index.php', [
            'categories' => $categories
        ]);
        return (new Response())->write($html);
    }


    public function store(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $response;
    }
}
