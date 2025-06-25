<?php

namespace NebboO\LaravelSheetParser\Exceptions;

use Exception;

class InvalidFileTypeException extends Exception
{
    protected $message = 'Only CSV files are supported.';
}