<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use models\Chatroom;
use database\DB;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../models/Chatroom.php';

$app = AppFactory::create();
$app->group('/chatrooms',function ($app){
    $app->get('', function (Request $request, Response $response) {
        $db = new DB('sqlite:slim-chatroom.db');
        $result =new Chatroom($db);
        $response->getBody()->write(json_encode($result->getAll('chatrooms')));
        return $response->withHeader('Content-Type','application/json');
    });
    $app->post('', function (Request $request, Response $response) {
        $inputs = $request->getParsedBody();
        $db = new DB('sqlite:slim-chatroom.db');
        $chatroom = new Chatroom($db);
        $result = $chatroom->create('chatrooms', [
            'name' => $inputs['name']
        ]);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    });
    $app->get('/{id}', function (Request $request, Response $response, array $args) {
        $db = new DB('sqlite:slim-chatroom.db');
        $chatroom =new Chatroom($db);
        $result = $chatroom->find('chatrooms',$args['id']);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type','application/json');
    });
    $app->get('/{id}/users', function (Request $request, Response $response, array $args) {
        $db = new DB('sqlite:slim-chatroom.db');
        $user = new Chatroom($db);
        $result = $user->find('chatrooms', $args['id']);
        $chatrooms = $user->users($result);
        $response->getBody()->write(json_encode($chatrooms));
        return $response->withHeader('Content-Type', 'application/json');
    });
    $app->get('/{id}/join', function (Request $request, Response $response, array $args) {
        $db = new DB('sqlite:slim-chatroom.db');
        $user = new Chatroom($db);
        $result = $user->find('chatrooms', $args['id']);
        $chatrooms = $user->users($result);
        $response->getBody()->write(json_encode($chatrooms));
        return $response->withHeader('Content-Type', 'application/json');
    });
});

