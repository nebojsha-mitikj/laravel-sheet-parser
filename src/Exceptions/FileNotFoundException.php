<?php

namespace NebboO\LaravelSheetParser\Exceptions;

use Exception;

class FileNotFoundException extends Exception
{
    protected $message = 'File not found.';
}