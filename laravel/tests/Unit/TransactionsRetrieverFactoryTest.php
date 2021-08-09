<?php

namespace Tests\Unit;

use App\Services\TransactionsRetriever;
use App\Services\TransactionsRetrieverFactory;
use PHPUnit\Framework\TestCase;

class TransactionsRetrieverFactoryTest extends TestCase
{
    public function testThrowsExceptionOnUnknownSource(): void
    {
        // arrange

        // assert
        $this->expectException(\InvalidArgumentException::class);

        // act
        TransactionsRetrieverFactory::create('html');
    }

    /**
     * @dataProvider validSources
     */
    public function testCreatesRetrieverForKnownSource(string $source): void
    {
        // arrange

        // act
        $retriever = TransactionsRetrieverFactory::create($source);

        // assert
        $this->assertInstanceOf(TransactionsRetriever::class, $retriever);
    }

    public function validSources(): \Generator
    {
        yield ['csv'];
        yield ['db'];
    }
}
