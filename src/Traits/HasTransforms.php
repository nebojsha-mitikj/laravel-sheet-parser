<?php

declare(strict_types=1);

namespace NebboO\LaravelSheetParser\Traits;

use Illuminate\Support\Collection;
use NebboO\LaravelSheetParser\Exceptions\FileNotFoundException;
use NebboO\LaravelSheetParser\Exceptions\FileNotReadableException;
use NebboO\LaravelSheetParser\Exceptions\GoogleSheetDownloadException;
use NebboO\LaravelSheetParser\Exceptions\InvalidFileTypeException;
use NebboO\LaravelSheetParser\Exceptions\InvalidGoogleSheetUrlException;

trait HasTransforms
{

    abstract protected function parsed(): array;

    /**
     * @throws GoogleSheetDownloadException
     * @throws InvalidGoogleSheetUrlException
     * @throws InvalidFileTypeException
     * @throws FileNotReadableException
     * @throws FileNotFoundException
     */
    public function headers(): array
    {
        return $this->parsed()[0] ?? [];
    }

    /**
     * @throws InvalidFileTypeException
     * @throws GoogleSheetDownloadException
     * @throws InvalidGoogleSheetUrlException
     * @throws FileNotReadableException
     * @throws FileNotFoundException
     */
    public function toArray(): array
    {
        $rows = $this->parsed();
        $headers = $this->headers();
        return array_map(
            fn($row) => array_combine($headers, $row),
            array_slice($rows, 1)
        );
    }

    /**
     * @throws GoogleSheetDownloadException
     * @throws InvalidGoogleSheetUrlException
     * @throws InvalidFileTypeException
     * @throws FileNotReadableException
     * @throws FileNotFoundException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * @throws GoogleSheetDownloadException
     * @throws InvalidGoogleSheetUrlException
     * @throws FileNotReadableException
     * @throws InvalidFileTypeException
     * @throws FileNotFoundException
     */
    public function toCollection(): Collection
    {
        return collect($this->toArray());
    }

    /**
     * Returns the number of data rows (excluding header).
     * @throws GoogleSheetDownloadException
     * @throws InvalidGoogleSheetUrlException
     * @throws InvalidFileTypeException
     * @throws FileNotReadableException
     * @throws FileNotFoundException
     */
    public function count(): int
    {
        return max(0, count($this->parsed()) - 1);
    }

    /**
     * @throws GoogleSheetDownloadException
     * @throws InvalidGoogleSheetUrlException
     * @throws FileNotReadableException
     * @throws InvalidFileTypeException
     * @throws FileNotFoundException
     */
    public function row(int $index): ?array
    {
        $rows = $this->toArray();
        return $rows[$index] ?? null;
    }

}