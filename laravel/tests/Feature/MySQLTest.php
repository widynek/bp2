<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Database\ConnectionInterface;
use Tests\TestCase;

class MySQLTest extends TestCase
{
    /**
     * @var ConnectionInterface
     */
    private $queryBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->queryBuilder = $this->app->make(ConnectionInterface::class);
        $this->waitForMySQL();

        $this->seed();
    }

    public function testTransactionsTableHasRecords(): void
    {
        // arrange

        // act
        $actualCount = $this->queryBuilder->table('transactions')->count();

        // assert
        $this->assertGreaterThan(0, $actualCount);
    }

    private function waitForMySQL(): void
    {
        $numberOfTries = 20;

        for ($i = 1; $i < $numberOfTries; $i++) {
            try {
                $this->queryBuilder->statement('SHOW TABLES');
                return;
            } catch (\PDOException $e) {
                sleep(1);
                $msg = (string) $e;
            }
        }

        $this->fail('Could not connect to MySQL - giving up after: ' . $i . ' tries. Last error: ' . $msg);
    }
}
