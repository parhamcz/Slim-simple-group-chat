<?php

use Slim\Factory\AppFactory;
use Controllers\Chatroom\MessageController;
use Middlewares\Authentication;
use Slim\Routing\RouteCollectorProxy;


$app->add(new Authentication());

$app->group('/messages', function (RouteCollectorProxy $group) {
    $group->get('/chatroom/{id}', [MessageController::class, 'all_messages']);
    $group->post('/chatroom/{id}/send', [MessageController::class, 'send']);
});
