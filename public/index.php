<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Middlewares\ErrorHandle;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->add(new ErrorHandle());
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write(json_encode(['message' => 'hello world!']));
    return $response->withHeader('Content-Type', 'application/json');
});
// Routes
require_once __DIR__ . '/../routes/users.php';
require_once __DIR__ . '/../routes/chatrooms.php';
require_once __DIR__ . '/../routes/messages.php';
$app->run();