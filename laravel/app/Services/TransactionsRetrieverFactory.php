<?php

declare(strict_types=1);

namespace App\Services;

class TransactionsRetrieverFactory
{
    public static function create(string $source): TransactionsRetriever
    {
        switch ($source) {
            case 'db':
                return new TransactionsRetrieverDb();
            case 'csv':
                return new TransactionsRetrieverCsv();
        }

        throw new \InvalidArgumentException("Unknown source: '{$source}'");
    }
}
