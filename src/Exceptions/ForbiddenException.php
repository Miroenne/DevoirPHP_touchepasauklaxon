<?php

namespace App\Exceptions;

class ForbiddenException extends \RuntimeException
{
    protected int $statusCode;

    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->statusCode = 403;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
