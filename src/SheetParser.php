<?php

declare(strict_types=1);

namespace NebboO\LaravelSheetParser;

use NebboO\LaravelSheetParser\Interfaces\ParserInterface;
use NebboO\LaravelSheetParser\Parsers\CsvParser;
use NebboO\LaravelSheetParser\Parsers\GoogleSheetParser;

class SheetParser
{
    public static function fromCsv(string $path): ParserInterface {
        return new CsvParser($path);
    }

    public static function fromGoogleSheet(string $url): ParserInterface {
        return new GoogleSheetParser($url);
    }
}