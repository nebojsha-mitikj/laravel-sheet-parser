<?php

declare(strict_types=1);

namespace NebboO\LaravelSheetParser\Parsers;

use NebboO\LaravelSheetParser\Exceptions\FileNotFoundException;
use NebboO\LaravelSheetParser\Exceptions\FileNotReadableException;
use NebboO\LaravelSheetParser\Exceptions\InvalidFileTypeException;
use NebboO\LaravelSheetParser\Interfaces\ParserInterface;
use NebboO\LaravelSheetParser\Traits\HasTransforms;

class CsvParser implements ParserInterface
{

    use HasTransforms;

    private array $data = [];

    public function __construct(private readonly string $path)
    {}

    /**
     * @throws FileNotFoundException
     * @throws InvalidFileTypeException
     * @throws FileNotReadableException
     */
    private function validate(): void
    {
        if (!file_exists($this->path)) {
            throw new FileNotFoundException();
        }
        if (!is_readable($this->path)) {
            throw new FileNotReadableException();
        }

        if (strtolower(pathinfo($this->path, PATHINFO_EXTENSION)) !== 'csv') {
            throw new InvalidFileTypeException();
        }
    }

    /**
     * @throws FileNotFoundException
     * @throws InvalidFileTypeException
     * @throws FileNotReadableException
     */
    private function parsed(): array
    {
        if (!empty($this->data)) {
            return $this->data;
        }
        $this->validate();
        $rows = [];
        if (($handle = fopen($this->path, 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                $rows[] = $row;
            }
            fclose($handle);
        }
        $this->data = $rows;
        return $this->data;
    }
}