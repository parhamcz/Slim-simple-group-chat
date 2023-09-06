<?php

namespace Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Controller
{
    public function write(Response $response , $writable)
    {
        $response->getbody()->write(json_encode($writable));
        return $response->withHeader('Content-Type','application/json');
    }
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