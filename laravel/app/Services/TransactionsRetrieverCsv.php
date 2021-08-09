<?php

declare(strict_types=1);

namespace App\Services;

class TransactionsRetrieverCsv implements TransactionsRetriever
{
    private const COLUMN_SEPARATOR = ',';

    /**
     * @var string
     */
    private string $pathToCsvFile;

    public function __construct(string $pathToCsvFile)
    {
        $this->pathToCsvFile = $pathToCsvFile;
    }

    public function retrieve(): array
    {
        $lines = $this->readLines();

        if (count($lines) === 0) {
            return [];
        }

        $lineWithColumnNames = \array_shift($lines);

        $columnNames = $this->parseLine($lineWithColumnNames);

        $transactions = [];

        foreach ($lines as $line) {
            $rowValues = $this->parseLine($line);
            $transaction = \array_combine($columnNames, $rowValues);
            $transaction['id'] = (int) $transaction['id'];
            $transaction['user_id'] = (int) $transaction['user_id'];
            $transactions[] = $transaction;
        }

        return $transactions;
    }

    private function readLines(): array
    {
        if (!\file_exists($this->pathToCsvFile)) {
            throw new \InvalidArgumentException("File {$this->pathToCsvFile}' does not exist");
        }

        if (!\is_readable($this->pathToCsvFile)) {
            throw new \InvalidArgumentException("File {$this->pathToCsvFile}' is not readable");
        }

        $lines = \file($this->pathToCsvFile);

        if ($lines === false) {
            throw new \RuntimeException("File '{$this->pathToCsvFile}' could not be read");
        }

        return $lines;
    }

    private function parseLine(string $line): array
    {
        return \str_getcsv($line, self::COLUMN_SEPARATOR);
    }
}
