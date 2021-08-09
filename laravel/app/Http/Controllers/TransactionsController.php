<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\TransactionsRetrieverFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransactionsController
{
    private TransactionsRetrieverFactory $retrieverFactory;

    public function __construct(TransactionsRetrieverFactory $retrieverFactory)
    {
        $this->retrieverFactory = $retrieverFactory;
    }

    public function read(Request $request): Response
    {
        $source = $request->get('source', '');

        try {
            $retriever = $this->retrieverFactory->create($source);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        $transactions = $retriever->retrieve();

        return new JsonResponse($transactions);
    }
}
