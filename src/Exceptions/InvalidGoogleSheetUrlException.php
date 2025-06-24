<?php

declare(strict_types=1);

namespace NebboO\LaravelSheetParser\Exceptions;

use Exception;

class InvalidGoogleSheetUrlException extends Exception
{
    protected $message = 'Invalid Google Sheet URL.';
}