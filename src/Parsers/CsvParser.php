<?php

declare(strict_types=1);

namespace NebboO\LaravelSheetParser\Parsers;
use NebboO\LaravelSheetParser\Interfaces\ParserInterface;
use NebboO\LaravelSheetParser\Traits\HasTransforms;

class CsvParser implements ParserInterface
{

    use HasTransforms;

    public function __construct(private readonly string $path)
    {}

    public function toArray(): array
    {
        if (!file_exists($this->path) || !is_readable($this->path)) {
            return [];
        }

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

}