<?php

use Slim\Factory\AppFactory;
use Controllers\Chatroom\ChatroomController;
use Middlewares\Authentication;
require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->add(new Authentication());

$app->group('/chatrooms', function ($app) {
    $app->get('', [ChatroomController::class, 'all_chatrooms']);
    $app->post('', [ChatroomController::class, 'create']);
    $app->get('/{id}', [ChatroomController::class, 'show']);
    $app->get('/{id}/join', [ChatroomController::class, 'join']);
    $app->get('/{id}/leave', [ChatroomController::class, 'leave']);
});

