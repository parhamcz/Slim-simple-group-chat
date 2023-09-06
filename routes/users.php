<?php

use Slim\Factory\AppFactory;
use Controllers\User\UserController;
use Middlewares\Authentication;
require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->add(new Authentication());

$app->group('/users', function ($app) {
    $app->get('', [UserController::class, 'all_users']);
    $app->post('', [UserController::class, 'create']);
    $app->get('/your-chatrooms', [UserController::class,'your_chatrooms']);
    $app->get('/{id}',[UserController::class,'show']);
});


