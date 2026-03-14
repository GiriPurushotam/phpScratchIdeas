<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contracts\RequestValidatorFactoryInterface;
use App\DataObjects\RegisterTransactionData;
use App\Exceptions\ValidationException;
use App\Formatter\ResponseFormatter;
use App\Http\Response;
use App\Http\ResponseInterface;
use App\Http\ServerRequestInterface;
use App\RequestValidators\CreateTransactionRequestValidator;
use App\RequestValidators\UpdateTransactionRequestValidator;
use App\Services\CategoryService;
use App\Services\TransactionService;
use App\ViewRenderer;

class TransactionsController
{
    public function __construct(
        private readonly ViewRenderer $view,
        private readonly CategoryService $categoryService,
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly TransactionService $transactionService,
        private readonly ResponseFormatter $responseFormatter
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
        $data = $this->requestValidatorFactory->make(CreateTransactionRequestValidator::class)->validate($request->getParsedBody());

        $user = $request->getAttribute('user');

        $this->transactionService->create($data, $user);
        return $response->withHeader('Location', BASE_PATH . '/transactions')->withStatus(302);
    }


    public function load(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        $params = $request->getQueryParams();
        $user = $request->getAttribute('user');

        $start = (int) ($params['start'] ?? 0);
        $length = (int) ($params['length'] ?? 10);
        $draw = (int) ($params['draw'] ?? 1);

        $orderColumnIndex = $params['order'][0]['column'] ?? 0;
        $orderBy = $params['columns'][$orderColumnIndex]['data'] ?? 'createdAt';
        $orderDir = $params['order'][0]['dir'] ?? 'desc';

        $search = $params['search']['value'] ?? '';

        $paginator = $this->transactionService->getPaginatedTransactions(
            $user->getId(),
            $start,
            $length,
            $orderBy,
            $orderDir,
            $search
        );

        $data = array_map(fn(array $transaction) => [
            'id' => $transaction['id'],
            'description' => $transaction['description'],
            'amount' =>  number_format((float) $transaction['amount']),
            'date' => date('m/d/Y', strtotime($transaction['date'])),
            'category' => $transaction['category'],
            'created_at' => date('m/d/Y g:i A', strtotime($transaction['created_at'])),
            'updated_at' => date('m/d/Y g:i A', strtotime($transaction['updated_at'])),
        ], $paginator->items());

        return $this->responseFormatter->asJson($response, [
            'draw' => $draw,
            'recordsTotal' => $paginator->total(),
            'recordsFiltered' => $paginator->filtered(),
            'data' => $data
        ]);
    }


    public function get(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $transaction = $this->transactionService->getById((int) $args['id']);

        if (! $transaction) {
            return $response->withStatus(404);
        }

        $data = [
            'id' => $transaction->getId(),
            'description' => $transaction->getDescription(),
            'amount' => $transaction->getAmount(),
            'date' => $transaction->getDate()->format('Y-m-d'),
            'category_id' => $transaction->getCategoryId(),
        ];

        return $this->responseFormatter->asJson($response, $data);
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $this->transactionService->delete((int) $args['id']);
        return $response;
    }


    public function update(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        try {
            $validationData = $this->requestValidatorFactory
                ->make(UpdateTransactionRequestValidator::class)
                ->validate(array_merge($request->getParsedBody(), $args));

            $transaction = $this->transactionService->getById((int)$args['id']);

            if (!$transaction) {
                return $response->withJson(['error' => 'Transaction not found'], 404);
            }

            $this->transactionService->update($transaction, $validationData);

            return $response->withJson(['success' => true]);
        } catch (\App\Exceptions\ValidationException $e) {
            return $response->withJson($e->errors, 422);
        }
    }
}
