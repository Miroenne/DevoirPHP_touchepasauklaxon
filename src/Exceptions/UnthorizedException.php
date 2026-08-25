<?php

namespace App\Exceptions;

class UnthorizedException extends \RuntimeException
{
    protected int $statusCode;

    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->statusCode = 401;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
