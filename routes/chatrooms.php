<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use models\Chatroom;
use database\DB;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../models/Chatroom.php';

$app = AppFactory::create();

$app->get('/chatrooms', function (Request $request, Response $response) {
    $db = new DB('sqlite:slim-chatroom.db');
    $result =new Chatroom($db);
    $response->getBody()->write(json_encode($result->getAll('chatrooms')));
    return $response->withHeader('Content-Type','application/json');
});
