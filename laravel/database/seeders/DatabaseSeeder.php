<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->loadTransactionsFromDump();
    }

    private function loadTransactionsFromDump(): void
    {
        /**
         * @var ConnectionInterface $dbConn
         */
        $dbConn = $this->container->make(ConnectionInterface::class);

        $dbConn->statement('DROP TABLE IF EXISTS transactions');

        $pathToDump = $this->container->databasePath('/dumps/transactions.sql');

        if (!\file_exists($pathToDump)) {
            throw new \RuntimeException('No dump found in ' . $pathToDump);
        }
        $rawSql = \file_get_contents($pathToDump);

        $dbConn->unprepared($rawSql);
    }
}
