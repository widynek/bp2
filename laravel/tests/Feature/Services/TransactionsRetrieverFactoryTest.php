<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Services\TransactionsRetriever;
use App\Services\TransactionsRetrieverFactory;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\ConnectionInterface;
use Tests\TestCase;

class TransactionsRetrieverFactoryTest extends TestCase
{
    public function testThrowsExceptionOnUnknownSource(): void
    {
        // arrange
        $factory = $this->buildFactory();

        // assert
        $this->expectException(\InvalidArgumentException::class);

        // act
        $factory->create('html');
    }

    private function buildFactory(): TransactionsRetrieverFactory
    {
        return new TransactionsRetrieverFactory(
            $this->app->make(Repository::class),
            $this->app->make(ConnectionInterface::class),
        );
    }

    /**
     * @dataProvider validSources
     */
    public function testCreatesRetrieverForKnownSource(string $source): void
    {
        // arrange
        $factory = $this->buildFactory();

        // act
        $retriever = $factory->create($source);

        // assert
        $this->assertInstanceOf(TransactionsRetriever::class, $retriever);
    }

    public function validSources(): \Generator
    {
        yield [
            'source' => 'csv',
        ];

        yield [
            'source' => 'db',
        ];
    }
}
