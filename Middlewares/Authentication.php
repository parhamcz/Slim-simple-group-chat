<?php

namespace Middlewares;

use database\DB;
use models\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Authentication implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $usernameHeader = $request->getHeaderLine('username');
        $db = new DB('sqlite:slim-chatroom.db');
        $user_instance = new User($db);
        if (!empty($usernameHeader)) {
            $user = $user_instance->findByCol('users', 'username', $usernameHeader);
            if ($user != null) {
                return $handler->handle($request);
            }
        }
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(
            [
                'status' => false,
                'message' => 'Authentication needed',
                'data' => [],
                'status_code' => 401
            ]
        ));

        return $response->withHeader('Content-Type','application.json')->withStatus(401);
    }
}
