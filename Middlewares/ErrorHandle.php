<?php

namespace Middlewares;

use Controllers\Controller;
use database\DB;
use models\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandle implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        // Check if the response body contains 'fatal error'
        $body = (string)$response->getBody();
        if (strpos($body, 'fatal error')) {
            // Modify the response body to '500'
            $response = $response->withStatus(500);
            $response->getBody()->rewind();
            $response->getBody()->write('500');
        }

        return $response;
    }
}
