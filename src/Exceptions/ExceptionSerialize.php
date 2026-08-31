<?php

namespace App\Exceptions;

class ExceptionSerialize
{

    public function __contruct() {}


    public function serializeException(string $message, int $code): array
    {
        return [
            'message' => $message,
            'responseCode' => $code
        ];
    }
}
