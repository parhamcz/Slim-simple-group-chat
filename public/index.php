<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../database/DB.php';
require __DIR__ . '/../models/User.php';
$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write(json_encode(['message' => 'hello world!']));
    return $response->withHeader('Content-Type', 'application/json');
});
//users routes
require_once __DIR__ . '/../routes/users.php';
require_once __DIR__ . '/../routes/chatrooms.php';
$app->run();