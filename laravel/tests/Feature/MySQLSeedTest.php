<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Database\ConnectionInterface;
use Tests\TestCase;

class MySQLSeedTest extends TestCase
{
    /**
     * @var ConnectionInterface
     */
    private $dbConnection;

    public function setUp(): void
    {
        parent::setUp();
        $this->dbConnection = $this->app->make(ConnectionInterface::class);
        $this->waitForMySQL($this->dbConnection);
        $this->clearMySQL();
    }

    public function testDatabaseConnectionWorks(): void
    {
        // arrange

        // act
        $result = $this->dbConnection->select('SHOW DATABASES');

        // assert
        $this->assertIsArray($result);
    }

    public function testTransactionTableDoesNotExist(): void
    {
        // arrange

        // act
        $tables = $this->dbConnection->select('SHOW TABLES LIKE "transactions"');

        // assert
        $this->assertSame([], $tables);
    }

    public function testTransactionsTableHasRecordsAfterSeeding(): void
    {
        // arrange
        $this->seed();

        // act
        $actualCount = $this->dbConnection->table('transactions')->count();

        // assert
        $this->assertGreaterThan(0, $actualCount);
    }
}
