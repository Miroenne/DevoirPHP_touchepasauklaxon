<?php

namespace App\Exceptions;

class ResourceNotFoundException extends \RuntimeException
{
    protected int $statusCode;

    public function __construct(string $message = 'Requested resource was not found')
    {
        parent::__construct($message);
        $this->statusCode = 404;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
