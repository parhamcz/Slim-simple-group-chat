<?php

use database\DB;
use models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/users', function (Request $request, Response $response) {
    $db = new DB('sqlite:slim-chatroom.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $user = new User($db);
    $users = $user->getAll('users');
    $response->getBody()->write(json_encode($users));
    return $response->withHeader('Content-Type', 'application/json');
});
$app->post('/users', function (Request $request, Response $response) {
    $inputs = $request->getParsedBody();
    $db = new DB('sqlite:slim-chatroom.db');
    $user = new User($db);
    $users = $user->create('users', [
        'display_name' => $inputs['username'],
        'username' => $inputs['display_name']
    ]);
    $response->getBody()->write(json_encode($users));
    return $response->withHeader('Content-Type', 'application/json');
});
$app->get('/users/{id}', function (Request $request, Response $response, array $args) {
    $db = new DB('sqlite:slim-chatroom.db');
    $user = new User($db);
    $users = $user->find('users', $args['id']);
    $response->getBody()->write(json_encode($users));
    return $response->withHeader('Content-Type', 'application/json');
});
