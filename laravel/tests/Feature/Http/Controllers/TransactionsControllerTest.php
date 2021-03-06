<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TransactionsControllerTest extends TestCase
{
    private ConnectionInterface $dbConnection;

    public function setUp(): void
    {
        parent::setUp();
        $this->dbConnection = $this->app->make(ConnectionInterface::class);
    }

    public function testEmitsBadRequestOnUnknownSource(): void
    {
        // arrange

        // act
        $response = $this->get('/api/transactions?source=html');

        // assert
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testRespondsWithJsonOnDbRequest(): void
    {
        // arrange
        $this->arrangeDbWithTransactions();

        // act
        $response = $this->get('/api/transactions?source=db');

        // assert
        $this->assertRespondedWithJson($response);
    }

    private function arrangeDbWithTransactions(): void
    {
        $this->waitForDb($this->dbConnection);
        $this->clearDb();
        $this->loadDbDumpFromFile($this->dbConnection, 'tests/Artefacts/transactions.sql');
    }

    public function testRespondsWithJsonOnCsvRequest(): void
    {
        // arrange
        $this->arrangeCsvFileWithTransactions();

        // act
        $response = $this->get('/api/transactions?source=csv');

        // assert
        $this->assertRespondedWithJson($response);
    }

    private function arrangeCsvFileWithTransactions(): void
    {
        /**
         * @var Repository $config
         */
        $config = $this->app->make(Repository::class);
        $config->set('transactions.csv.path', 'tests/Artefacts/transactions.csv');
    }

    private function assertRespondedWithJson(TestResponse $response): void
    {
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $decoded = \json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        $this->assertCount(2, $decoded);
        $actualKeys = \array_keys($decoded[0]);
        $expectedKeys = ['id', 'code', 'amount', 'user_id', 'created_at', 'updated_at'];
        $this->assertSame($expectedKeys, $actualKeys);
    }
}
