<?php

use Slim\Factory\AppFactory;
use Controllers\Chatroom\ChatroomController;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->group('/chatrooms', function ($app) {
    $app->get('', [ChatroomController::class, 'all_chatrooms']);
    $app->post('', [ChatroomController::class, 'create']);
    $app->get('/{id}', [ChatroomController::class, 'show']);
    $app->get('/{id}/join', [ChatroomController::class, 'join']);
});

