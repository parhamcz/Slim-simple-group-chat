<?php

namespace Controllers;

class Controller
{
    public function result(bool $status, string $message, $data, $code = 200): array
    {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'status code' => $code
        ];
    }

    public function notFound(string $message)
    {
        return [
            'status' => false,
            'message' => $message,
            'data' => [],
            'status code' => 404
        ];
    }
}