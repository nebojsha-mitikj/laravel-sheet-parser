<?php

declare(strict_types=1);

namespace NebboO\LaravelSheetParser\Parsers;

use GuzzleHttp\Client;
use NebboO\LaravelSheetParser\Interfaces\ParserInterface;
use NebboO\LaravelSheetParser\Traits\HasTransforms;

class GoogleSheetParser implements ParserInterface
{

    use HasTransforms;

    public function __construct(private readonly string $url)
    {}

    private function getCsvUrl(): ?string
    {
        if (empty($this->url) || !preg_match('#/d/([a-zA-Z0-9-_]+)#', $this->url, $matches)) {
            return null;
        }

        $sheetId = $matches[1];

        $gid = 0;

        if (preg_match('#gid=([0-9]+)#', $this->url, $gidMatch)) {
            $gid = $gidMatch[1];
        }

        return "https://docs.google.com/spreadsheets/d/{$sheetId}/export?format=csv&gid={$gid}";
    }

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
            return [];
        }
    }

    public function toArray(): array
    {
        $rows = $this->getRows();
        $headers = array_shift($rows);
        return array_map(fn($row) => array_combine($headers, $row), $rows);
    }

}