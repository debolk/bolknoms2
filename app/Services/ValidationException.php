<?php

namespace App\Services;

use Exception;

class ValidationException extends Exception
{
    private $error_messages;

    public function __construct($error_messages)
    {
        $this->error_messages = $error_messages;
    }

    public function messages()
    {
        return $this->error_messages;
    }
}
