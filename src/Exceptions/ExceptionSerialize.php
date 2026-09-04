<?php

namespace App\Exceptions;

class ExceptionSerialize
{

    public function __construct() {}


    public function serializeException(string $message, int $code): array
    {
        return [
            'message' => $message,
            'responseCode' => $code
        ];
    }
}
