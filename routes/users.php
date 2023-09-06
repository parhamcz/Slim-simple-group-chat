<?php

use Slim\Factory\AppFactory;
use Controllers\User\UserController;
use Middlewares\Authentication;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->add(new Authentication());

$app->group('/users', function (RouteCollectorProxy $group) {
    $group->get('', [UserController::class, 'all_users']);
    $group->post('', [UserController::class, 'create']);
    $group->get('/your-chatrooms', [UserController::class,'your_chatrooms']);
    $group->get('/{id}',[UserController::class,'show']);
});


