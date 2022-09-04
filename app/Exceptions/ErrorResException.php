<?php

namespace App\Exceptions;

use Exception;

class ErrorResException extends Exception
{
    function __construct($message, $statusCode = 500)
    {
        parent::__construct($message, $statusCode);
    }
}
