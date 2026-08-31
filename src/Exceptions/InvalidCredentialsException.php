<?php

namespace App\Exceptions;

class InvalidCredentialsException extends \RuntimeException
{

    protected int $statusCode;

    public function __construct(string $message = 'Invalid Credentials, connection failed')
    {
        parent::__construct($message);
        $this->statusCode = 401;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
