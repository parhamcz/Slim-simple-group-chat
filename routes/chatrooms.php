<?php

use Slim\Factory\AppFactory;
use Controllers\Chatroom\ChatroomController;
use Middlewares\Authentication;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->add(new Authentication());

$app->group('/chatrooms', function (RouteCollectorProxy $group) {
    $group->get('', [ChatroomController::class, 'all_chatrooms']);
    $group->post('', [ChatroomController::class, 'create']);
    $group->get('/{id}', [ChatroomController::class, 'show']);
    $group->get('/{id}/join', [ChatroomController::class, 'join']);
    $group->get('/{id}/leave', [ChatroomController::class, 'leave']);
    $group->get('/{id}/destroy', [ChatroomController::class, 'destroy']);
    $group->get('/{chatroom_id}/user/{user_id}/set-admin', [ChatroomController::class, 'set_admin']);
});

