<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\TransactionsRetrieverCsv;
use PHPUnit\Framework\TestCase;

class TransactionsRetrieverCsvTest extends TestCase
{
    public function testThrowsExceptionWhenFileWithTransactionDoesNotExist(): void
    {
        // arrange
        $retriever = new TransactionsRetrieverCsv('non-existing.csv');

        // assert
        $this->expectException(\InvalidArgumentException::class);

        // act
        $retriever->retrieve();
    }

    public function testReturnsEmptyArrayForEmptyFile(): void
    {
        // arrange
        $retriever = new TransactionsRetrieverCsv('tests/Artefacts/empty_file');

        // act
        $content = $retriever->retrieve();

        // assert
        $this->assertSame([], $content);
    }

    public function testBuildsAssociativeArrayWithColumnNames(): void
    {
        // arrange
        $retriever = new TransactionsRetrieverCsv('tests/Artefacts/transactions.csv');

        // act
        $content = $retriever->retrieve();

        // assert
        $this->assertArrayContainsAllRowsIndexedByColumnNames($content);
    }

    private function assertArrayContainsAllRowsIndexedByColumnNames(array $content): void
    {
        $expectedContent = [
            [
                'id' => 1,
                'code' => 'T_218_ljydmgebx',
                'amount' => 8617.19,
                'user_id' => 375,
                'created_at' => '2020-01-19 16:08:59',
                'updated_at' => '2020-01-19 16:08:59',
            ],
            [
                'id' => 2,
                'code' => 'T_335_wmhrbjxld',
                'amount' => 6502.72,
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
        $retriever = new TransactionsRetrieverCsv('tests/Artefacts/transactions.csv');

        // act
        $content = $retriever->retrieve();

        // assert
        $this->assertSame($content, $retriever->retrieve());
    }
}
