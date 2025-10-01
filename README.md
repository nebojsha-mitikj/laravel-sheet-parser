# Laravel Sheet Parser 🚀
A Laravel package to parse data from CSV files and public Google Sheets using a unified interface.

## Features
- Parse CSV files or public Google Sheets seamlessly.
- Consistent API for both data sources.
- Convert to arrays, JSON, or Laravel collections.
- Retrieve rows, columns, headers, or specific data points.
---
## Installation
Require the package via Composer:
```bash
composer require nebbo.o/laravel-sheet-parser
```
---
## Usage
### 1. Parsing CSV files
```php
use NebboO\LaravelSheetParser\SheetParser;

$parser = SheetParser::fromCsv(storage_path('data/example.csv'));

$data = $parser->toArray(); // Convert to array
$json = $parser->toJson(); // Convert to JSON
$collection = $parser->toCollection(); // Convert to collection
```

### 2. Parsing Google Sheets
Make sure the sheet is publicly accessible (Anyone with the link can view).
```php
use NebboO\LaravelSheetParser\SheetParser;

$url = 'https://docs.google.com/spreadsheets/d/your-sheet-id/edit?usp=sharing';
$parser = SheetParser::fromGoogleSheet($url);

$data = $parser->toArray(); // Convert to array
$json = $parser->toJson(); // Convert to JSON
$collection = $parser->toCollection(); // Convert to collection
```
---
## API Reference
Both ```CsvParser``` and ```GoogleSheetParser``` implement the same ```ParserInterface```.
#### Methods
<table>
  <tr>
    <th style="min-width:410px;text-align:left">Method</th>
    <th style="min-width:300px;text-align:left">Description</th>
  </tr>
  <tr>
    <td><code>toArray(): array</code></td>
    <td>Returns the data as an array of associative arrays (using headers as keys).</td>
  </tr>
  <tr>
    <td><code>toJson(): string</code></td>
    <td>Returns the data as JSON.</td>
  </tr>
  <tr>
    <td><code>toCollection(): \Illuminate\Support\Collection</code></td>
    <td>Returns the data as a Laravel collection.</td>
  </tr>
  <tr>
    <td><code>headers(): array</code></td>
    <td>Returns the headers row.</td>
  </tr>
  <tr>
    <td><code>count(): int</code></td>
    <td>Returns the number of data rows (excluding headers).</td>
  </tr>
  <tr>
    <td><code>row(int $index): ?array</code></td>
    <td>Returns a specific row by index.</td>
  </tr>
  <tr>
    <td><code>column(string $header): array</code></td>
    <td>Returns all values from a specific column.</td>
  </tr>
  <tr>
    <td><code>first(): ?array</code></td>
    <td>Returns the first row of data.</td>
  </tr>
  <tr>
    <td><code>last(): ?array</code></td>
    <td>Returns the last row of data.</td>
  </tr>
  <tr>
    <td><code>hasHeader(string $header): bool</code></td>
    <td>Checks if a given header exists.</td>
  </tr>

</table>

---
## Error Handling
The package provides custom exceptions you can catch to handle errors gracefully:
- ```FileNotFoundException``` – CSV file does not exist.

- ```FileNotReadableException``` – CSV file cannot be read.

- ```InvalidFileTypeException``` – File is not a CSV.

- ```InvalidGoogleSheetUrlException``` – Invalid or missing Google Sheet URL.

- ```GoogleSheetDownloadException``` – Failed to fetch or parse Google Sheet data.
---
## Requirements
- ```PHP``` 8.1+
- ```Laravel``` 10, 11, or 12
---
## License
This package is open-sourced software licensed under the MIT license