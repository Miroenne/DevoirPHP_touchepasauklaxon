<?php

namespace App\Exceptions;

class InvalidArgumentException extends \RuntimeException
{

    protected int $statusCode;

    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->statusCode = 400;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
