<?php

namespace App\Responses;

class Response
{
    public static function render(string $view, array $data = []): void
    {
        http_response_code($data['statusCode'] ?? 200);
        extract($data);
        include __DIR__ . "/../Views/{$view}.php";
    }
}
