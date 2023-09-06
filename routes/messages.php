<?php
use Slim\Factory\AppFactory;
use Controllers\Chatroom\MessageController;
require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->group('/messages', function ($app) {
    $app->get('/chatroom/{id}', [MessageController::class, 'all_messages']);
    $app->post('/chatroom/{id}/send', [MessageController::class, 'send']);
});
