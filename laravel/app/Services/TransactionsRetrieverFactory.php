<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Contracts\Config\Repository;

class TransactionsRetrieverFactory
{
    private Repository $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    public function create(string $source): TransactionsRetriever
    {
        switch ($source) {
            case 'db':
                return new TransactionsRetrieverDb();
            case 'csv':
                $pathToCsv = $this->config->get('transactions.csv.path');
                return new TransactionsRetrieverCsv($pathToCsv);
        }

        throw new \InvalidArgumentException("Unknown source: '{$source}'");
    }
}
