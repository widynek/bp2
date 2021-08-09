<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Contracts\Container\Container;

class TransactionsRetrieverFactory
{
    /**
     * @var Container
     */
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(string $source): TransactionsRetriever
    {
        switch ($source) {
            case 'db':
                return new TransactionsRetrieverDb();
            case 'csv':
                $pathToCsv = $this->container->make('path.storage') . \DIRECTORY_SEPARATOR . 'transactions.csv';
                return new TransactionsRetrieverCsv($pathToCsv);
        }

        throw new \InvalidArgumentException("Unknown source: '{$source}'");
    }
}
