<?php

namespace App\Services;

use Illuminate\Support\MessageBag;

class ValidationException extends \Exception
{
    private $error_messages;

    public function __construct(MessageBag $error_messages)
    {
        parent::__construct();
        $this->error_messages = $error_messages;
    }

    public function messages(): MessageBag
    {
        return $this->error_messages;
    }
}
