<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Services\TransactionsRetriever;
use App\Services\TransactionsRetrieverFactory;
use Tests\TestCase;

class TransactionsRetrieverFactoryTest extends TestCase
{
    public function testThrowsExceptionOnUnknownSource(): void
    {
        // arrange
        $factory = new TransactionsRetrieverFactory($this->app);

        // assert
        $this->expectException(\InvalidArgumentException::class);

        // act
        $factory->create('html');
    }

    /**
     * @dataProvider validSources
     */
    public function testCreatesRetrieverForKnownSource(string $source): void
    {
        // arrange
        $factory = new TransactionsRetrieverFactory($this->app);

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
