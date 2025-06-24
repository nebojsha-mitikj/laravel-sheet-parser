<?php

namespace NebboO\LaravelSheetParser\Exceptions;

use Exception;

class GoogleSheetDownloadException extends Exception
{
    public function __construct(string $message = 'Failed to download or parse Google Sheet CSV.')
    {
        parent::__construct($message);
    }
}