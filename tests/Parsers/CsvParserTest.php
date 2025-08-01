<?php

declare(strict_types=1);

namespace NebboO\LaravelSheetParser\Tests\Parsers;

use Illuminate\Support\Collection;
use NebboO\LaravelSheetParser\Exceptions\FileNotFoundException;
use NebboO\LaravelSheetParser\Exceptions\InvalidFileTypeException;
use NebboO\LaravelSheetParser\Parsers\CsvParser;
use NebboO\LaravelSheetParser\Tests\TestCase;

class CsvParserTest extends TestCase
{

    protected string $testCsvFile =  __DIR__ . '/../../fixtures/test.csv';

    protected string $missingFile =  __DIR__ . '/../../fixtures/missing.csv';

    protected string $testTxtFile =  __DIR__ . '/../../fixtures/test.txt';

    protected array $csvData = [
        'name,email',
        'John,john@example.com',
        'Jane,jane@example.com',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        file_put_contents($this->testCsvFile, implode("\n", $this->csvData));
        file_put_contents($this->testTxtFile, implode("\n", $this->csvData));
    }

    protected function tearDown(): void
    {
        foreach ([$this->testTxtFile, $this->missingFile, $this->testCsvFile] as $filePath) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        parent::tearDown();
    }

    public function test_file_not_found_exception(): void
    {
        $this->expectException(FileNotFoundException::class);
        $parser = new CsvParser($this->missingFile);
        $parser->toArray();
    }

    public function test_invalid_file_type_exception(): void
    {
        $this->expectException(InvalidFileTypeException::class);
        $parser = new CsvParser($this->testTxtFile);
        $parser->toArray();
    }

    public function test_to_array_returns_parsed_rows()
    {
        $parser = new CsvParser($this->testCsvFile);
        $data = $parser->toArray();
        $this->assertCount(2, $data);
        $this->assertEquals('John', $data[0]['name']);
        $this->assertEquals('jane@example.com', $data[1]['email']);
    }

    public function test_to_collection_returns_a_collection_instance()
    {
        $parser = new CsvParser($this->testCsvFile);
        $collection = $parser->toCollection();
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertEquals('Jane', $collection[1]['name']);
    }

    public function test_to_json_returns_valid_json_string()
    {
        $parser = new CsvParser($this->testCsvFile);
        $json = $parser->toJson();
        $this->assertJson($json);
        $decoded = json_decode($json, true);
        $this->assertIsArray($decoded);
        $this->assertEquals('John', $decoded[0]['name']);
    }

    public function test_count()
    {
        $parser = new CsvParser($this->testCsvFile);
        $this->assertEquals(2, $parser->count());
    }

}