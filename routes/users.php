<?php

use database\DB;
use models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/users', function (Request $request, Response $response) {
    $a = new DB('sqlite:slim-chatroom.db');
    $a->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $user = new User($a);
    $users = $user->getAll('users');
    $response->getBody()->write(json_encode($users));
    return $response;
});
