<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Services\TransactionsRetrieverDb;
use Illuminate\Database\ConnectionInterface;
use Tests\TestCase;

class TransactionsRetrieverDbTest extends TestCase
{
    private ConnectionInterface $dbConnection;

    public function setUp(): void
    {
        parent::setUp();
        $this->dbConnection = $this->app->make(ConnectionInterface::class);
        $this->waitForDb($this->dbConnection);
        $this->clearDb();
    }

    public function testThrowsExceptionWhenTableDoesNotExist(): void
    {
        // arrange
        $retriever = $this->createRetriever();

        // assert
        $this->expectException(\Exception::class);

        // act
        $retriever->retrieve();
    }

    public function testReturnsEmptyArrayForEmptyTable(): void
    {
        // arrange
        $retriever = $this->arrangeEmptyTableAndCreateRetriever();

        // act
        $content = $retriever->retrieve();

        // assert
        $this->assertSame([], $content);
    }

    private function arrangeEmptyTableAndCreateRetriever(): TransactionsRetrieverDb
    {
        $this->loadDbDumpFromFile($this->dbConnection, 'tests/Artefacts/transactions.sql');
        $this->dbConnection->affectingStatement('TRUNCATE TABLE transactions');
        return $this->createRetriever();
    }

    public function testBuildsAssociativeArrayWithColumnNames(): void
    {
        // arrange
        $retriever = $this->arrangeTableWithTransactionsAndCreateRetriever();

        // act
        $content = $retriever->retrieve();

        // assert
        $this->assertArrayContainsAllRowsIndexedByColumnNames($content);
    }

    private function arrangeTableWithTransactionsAndCreateRetriever(): TransactionsRetrieverDb
    {
        $this->loadDbDumpFromFile($this->dbConnection, 'tests/Artefacts/transactions.sql');
        return $this->createRetriever();
    }

    private function assertArrayContainsAllRowsIndexedByColumnNames(array $content): void
    {
        $expectedContent = [
            [
                'id' => 1,
                'code' => 'T_218_ljydmgebx',
                'amount' => '8617.19',
                'user_id' => 375,
                'created_at' => '2020-01-19 16:08:59',
                'updated_at' => '2020-01-19 16:08:59',
            ],
            [
                'id' => 2,
                'code' => 'T_335_wmhrbjxld',
                'amount' => '6502.72',
                'user_id' => 1847,
                'created_at' => '2020-01-19 16:08:59',
                'updated_at' => '2020-01-19 16:08:59',
            ],
        ];

        $this->assertSame($expectedContent, $content);
    }

    public function testMultipleExecutionsProvidesEqualResults(): void
    {
        // arrange
        $retriever = $this->arrangeTableWithTransactionsAndCreateRetriever();

        // act
        $content = $retriever->retrieve();

        // assert
        $this->assertSame($content, $retriever->retrieve());
    }

    private function createRetriever(): TransactionsRetrieverDb
    {
        return new TransactionsRetrieverDb($this->dbConnection);
    }
}
