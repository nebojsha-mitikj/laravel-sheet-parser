<?php

declare(strict_types=1);

namespace NebboO\LaravelSheetParser\Tests\Parsers;

use Illuminate\Support\Collection;
use NebboO\LaravelSheetParser\Exceptions\GoogleSheetDownloadException;
use NebboO\LaravelSheetParser\Exceptions\InvalidGoogleSheetUrlException;
use NebboO\LaravelSheetParser\Parsers\GoogleSheetParser;
use NebboO\LaravelSheetParser\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class GoogleSheetParserTest extends TestCase
{

    private string $url = 'https://docs.google.com/spreadsheets/d/1MUiozCFm7jcnopILsJKIHnPja1tequcwx3B9WzRaZqg/edit?usp=sharing';

    public static function invalid_urls(): array
    {
        return [
            [''],
            ['hello'],
            ['https://docs.google.com'],
            ['https://docs.google.com/spreadsheets/1MUiozCFm7jcnopILsJKIHnPja1tequcwx3B9WzRaZqg/edit?usp=sharing'],
        ];
    }

    #[DataProvider('invalid_urls')]
    public function test_invalid_google_sheet_url_exception(string $url): void
    {
        $this->expectException(InvalidGoogleSheetUrlException::class);
        $parser = new GoogleSheetParser($url);
        $parser->toArray();
    }

    public function test_google_sheet_download_exception(): void
    {
        $this->expectException(GoogleSheetDownloadException::class);
        $parser = new GoogleSheetParser('https://docs.google.com/spreadsheets/d/1uC3Sjl7w9ujQ_3iSaq2RsvymdZbdXhbzwDJZWFSgVQU/edit?usp=sharing');
        $parser->toArray();
    }

    public function test_to_array_returns_parsed_rows()
    {
        $parser = new GoogleSheetParser($this->url);
        $data = $parser->toArray();
        $this->assertCount(2, $data);
        $this->assertEquals('John', $data[0]['name']);
        $this->assertEquals('jane@example.com', $data[1]['email']);
    }

    public function test_to_collection_returns_a_collection_instance()
    {
        $parser = new GoogleSheetParser($this->url);
        $collection = $parser->toCollection();
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertEquals('Jane', $collection[1]['name']);
    }

    public function test_to_json_returns_valid_json_string()
    {
        $parser = new GoogleSheetParser($this->url);
        $json = $parser->toJson();
        $this->assertJson($json);
        $decoded = json_decode($json, true);
        $this->assertIsArray($decoded);
        $this->assertEquals('John', $decoded[0]['name']);
    }

    public function test_count()
    {
        $parser = new GoogleSheetParser($this->url);
        $this->assertEquals(2, $parser->count());
    }

    public function test_non_existing_row()
    {
        $parser = new GoogleSheetParser($this->url);
        $this->assertNull($parser->row(10));
    }

    public function test_row_returns_correct_row()
    {
        $parser = new GoogleSheetParser($this->url);
        $this->assertEquals(['name' => 'John', 'email' => 'john@example.com'], $parser->row(0));
        $this->assertEquals(['name' => 'Jane', 'email' => 'jane@example.com'], $parser->row(1));
    }
}