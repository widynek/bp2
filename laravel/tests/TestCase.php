<?php

namespace Tests;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function waitForDb(ConnectionInterface $connection): void
    {
        $numberOfTries = 20;

        for ($i = 1; $i < $numberOfTries; $i++) {
            try {
                $connection->statement('SHOW TABLES');
                return;
            } catch (\PDOException $e) {
                sleep(1);
                $msg = (string) $e;
            }
        }

        $this->fail('Could not connect to MySQL - giving up after: ' . $i . ' tries. Last error: ' . $msg);
    }

    protected function clearDb(): void
    {
        $this->artisan('migrate:fresh');
    }

    protected function loadDbDumpFromFile(ConnectionInterface $connection, string $pathToDump): void
    {
        if (!\file_exists($pathToDump)) {
            throw new \RuntimeException('No dump found in ' . $pathToDump);
        }
        $rawSql = \file_get_contents($pathToDump);

        $connection->unprepared($rawSql);
    }
}
