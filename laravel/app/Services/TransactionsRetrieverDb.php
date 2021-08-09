<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Database\ConnectionInterface;

class TransactionsRetrieverDb implements TransactionsRetriever
{
    private const TABLE_NAME = 'transactions';

    private ConnectionInterface $dbConnection;

    public function __construct(ConnectionInterface $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function retrieve(): array
    {
        $tables = $this->dbConnection->select('SHOW TABLES LIKE "' . self::TABLE_NAME . '"');
        if (count($tables) === 0) {
            throw new \RuntimeException('DB table not exists');
        }

        $rows = $this->dbConnection->select(
            'SELECT id, code, amount, user_id, created_at, updated_at FROM ' . self::TABLE_NAME
        );

        $transactions = [];

        foreach ($rows as $row) {
            $transactions[] = [
                'id' => $row->id,
                'code' => $row->code,
                'amount' => $row->amount,
                'user_id' => $row->user_id,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ];
        }

        return $transactions;
    }
}
