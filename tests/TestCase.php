<?php

namespace NebboO\LaravelSheetParser\Tests;

use NebboO\LaravelSheetParser\Providers\SheetParserServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            SheetParserServiceProvider::class,
        ];
    }
}