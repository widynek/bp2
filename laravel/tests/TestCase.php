<?php

namespace Tests;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function waitForMySQL(ConnectionInterface $connection): void
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

    protected function clearMySQL(): void
    {
        $this->artisan('migrate:fresh');
    }
}
