<?php

declare(strict_types=1);

namespace NebboO\LaravelSheetParser\Parsers;

use GuzzleHttp\Client;
use NebboO\LaravelSheetParser\Exceptions\GoogleSheetDownloadException;
use NebboO\LaravelSheetParser\Exceptions\InvalidGoogleSheetUrlException;
use NebboO\LaravelSheetParser\Interfaces\ParserInterface;
use NebboO\LaravelSheetParser\Traits\HasTransforms;

class GoogleSheetParser implements ParserInterface
{

    use HasTransforms;

    public function __construct(private readonly string $url)
    {}

    /** @throws InvalidGoogleSheetUrlException */
    private function getCsvUrl(): ?string
    {
        if (empty($this->url) || !preg_match('#/d/([a-zA-Z0-9-_]+)#', $this->url, $matches)) {
            throw new InvalidGoogleSheetUrlException();
        }

        $sheetId = $matches[1];

        $gid = 0;

        if (preg_match('#gid=([0-9]+)#', $this->url, $gidMatch)) {
            $gid = $gidMatch[1];
        }

        return "https://docs.google.com/spreadsheets/d/{$sheetId}/export?format=csv&gid={$gid}";
    }

    /**
     * @throws GoogleSheetDownloadException
     * @throws InvalidGoogleSheetUrlException
     */
    private function getRows(): array
    {
        $csvUrl = $this->getCsvUrl();
        try {
            $client = new Client();
            $response = $client->get($csvUrl);
            $body = $response->getBody()->getContents();
            $lines = explode("\n", trim($body));
            return array_map('str_getcsv', $lines);
        } catch (\Throwable $e) {
            throw new GoogleSheetDownloadException(
                'Failed to download or parse Google Sheet CSV: ' . $e->getMessage()
            );
        }
    }

    public function toArray(): array
    {
        $rows = $this->getRows();
        $headers = array_shift($rows);
        return array_map(fn($row) => array_combine($headers, $row), $rows);
    }

}