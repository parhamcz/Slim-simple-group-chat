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
        $controller = new Controller();
        $response = $handler->handle($request);
        $body = (string)$response->getBody();
        if (strpos($body, 'fatal error')) {
            $response->getBody()->rewind();
            $response = $controller->write($response,$controller->result(
                false,
                'Internal Error',
                [],
                500
            ));
        }
        return $response;
    }
}
