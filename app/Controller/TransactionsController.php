<?php

declare(strict_types=1);

namespace App\Controller;

use App\Http\Response;
use App\Http\ResponseInterface;
use App\Http\ServerRequestInterface;
use App\ViewRenderer;

class TransactionsController
{
    public function __construct(
        private readonly ViewRenderer $view
    ) {}

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        $html = $this->view->render('/transactions/transactions_index.php');
        return (new Response())->write($html);
    }
}
