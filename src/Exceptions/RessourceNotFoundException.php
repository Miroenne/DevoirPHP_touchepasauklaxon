<?php

namespace App\Exceptions;

class RessourceNotFoundException extends \RuntimeException
{
    protected int $statusCode;

    public function __construct(string $message = 'Requested ressource was not found')
    {
        parent::__construct($message);
        $this->statusCode = 404;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
