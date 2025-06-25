<?php

namespace NebboO\LaravelSheetParser\Exceptions;

use Exception;

class FileNotReadableException extends Exception
{

    protected $message = 'File is not readable.';

}