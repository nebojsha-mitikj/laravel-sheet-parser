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
    public function toArray(): array
    {
        $this->validate();
        $rows = [];
        if (($handle = fopen($this->path, 'r')) !== false) {
            $headers = fgetcsv($handle);
            if (!$headers) {
                fclose($handle);
                return [];
            }
            while (($data = fgetcsv($handle)) !== false) {
                if (count($data) === count($headers)) {
                    $rows[] = array_combine($headers, $data);
                }
            }
            fclose($handle);
        }
        return $rows;
    }

    /**
     * @throws FileNotReadableException
     * @throws FileNotFoundException
     * @throws InvalidFileTypeException
     */
    public function headers(): array
    {
        $this->validate();
        if (($handle = fopen($this->path, 'r')) !== false) {
            $headers = fgetcsv($handle);
            fclose($handle);
            if (!$headers) {
                return [];
            }
            return $headers;
        }
        return [];
    }
}