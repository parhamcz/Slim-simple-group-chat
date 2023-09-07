<?php

namespace Middlewares;

use Controllers\Controller;
use database\DB;
use models\Chatroom;
use models\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Admin_Check implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = new \Slim\Psr7\Response();
        $controller = new Controller();
        $usernameHeader = $request->getHeaderLine('username');
        $db = new DB('sqlite:slim-chatroom.db');

        $user_instance = new User($db);
        $chatroom_instance = new Chatroom($db);

        $args = \Slim\Routing\RouteContext::fromRequest($request)->getRoute()->getArguments();

        $chatroom = $chatroom_instance->find('chatrooms', $args['id']);
        if (!$chatroom) {
            return $controller->write($response, $controller->notFound('Chatroom not found'));
        }

        $user = $user_instance->findByCol('users', 'username', $usernameHeader);
        if (!$user) {
            return $controller->write($response, $controller->notFound('User not found'));
        }

        if ($chatroom_instance->isAdmin($chatroom, $user)) {
            return $handler->handle($request);
        }
        $response->getBody()->write(json_encode(
            [
                'status' => false,
                'message' => 'User is not Authorized for this route',
                'data' => [],
                'status_code' => 401
            ]
        ));
        return $response->withHeader('Content-Type', 'application.json')->withStatus(401);
    }
}
