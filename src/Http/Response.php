<?php

namespace App\Http;

final class Response{

    public function __construct(
        public readonly string $body,
        public readonly int $statusCode = 200,
        public readonly string $contentType = 'application/json; charset=utf-8',
        public readonly array $headers = [],
    ){}

    public function html(string $body, int $statusCode = 200): self {
        return new self($body, $statusCode, 'text/html; charset=utf-8');
    }
}