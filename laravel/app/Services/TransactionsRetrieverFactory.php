<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\ConnectionInterface;

class TransactionsRetrieverFactory
{
    private Repository $config;
    private ConnectionInterface $dbConnection;

    public function __construct(Repository $config, ConnectionInterface $dbConnection)
    {
        $this->config = $config;
        $this->dbConnection = $dbConnection;
    }

    public function create(string $source): TransactionsRetriever
    {
        switch ($source) {
            case 'db':
                return new TransactionsRetrieverDb($this->dbConnection);
            case 'csv':
                $pathToCsv = $this->config->get('transactions.csv.path');
                return new TransactionsRetrieverCsv($pathToCsv);
        }

        throw new \InvalidArgumentException("Unknown source: '{$source}'");
    }
}
