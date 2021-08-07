<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MySQLTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ConnectionInterface
     */
    private $queryBuilder;

    public function setUp(): void
    {
        $this->markTestSkipped('todo failing test');
        parent::setUp();

        $this->seed();
        $this->queryBuilder = $this->app->make(ConnectionInterface::class);
    }

    public function testTransactionsTableHasRecords(): void
    {
        // arrange

        // act
        $actualCount = $this->queryBuilder->table('transactions')->count();

        // assert
        $this->assertGreaterThan(0, $actualCount);
    }
}
