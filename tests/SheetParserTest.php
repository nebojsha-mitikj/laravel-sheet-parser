<?php

declare(strict_types=1);

namespace NebboO\LaravelSheetParser\Tests;

use NebboO\LaravelSheetParser\Interfaces\ParserInterface;
use NebboO\LaravelSheetParser\Parsers\CsvParser;
use NebboO\LaravelSheetParser\Parsers\GoogleSheetParser;
use NebboO\LaravelSheetParser\SheetParser;

class SheetParserTest extends TestCase
{
    public function test_from_csv_returns_csv_parser(): void
    {
        $parser = SheetParser::fromCsv('');
        $this->assertInstanceOf(ParserInterface::class, $parser);
        $this->assertInstanceOf(CsvParser::class, $parser);
    }

    public function test_from_google_sheet_returns_google_sheet_parser(): void
    {
        $parser = SheetParser::fromGoogleSheet('');
        $this->assertInstanceOf(ParserInterface::class, $parser);
        $this->assertInstanceOf(GoogleSheetParser::class, $parser);
    }
}