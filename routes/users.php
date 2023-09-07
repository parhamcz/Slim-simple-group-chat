<?php

use Slim\Factory\AppFactory;
use Controllers\User\UserController;
use Middlewares\Authentication;
use Slim\Routing\RouteCollectorProxy;


//$app = AppFactory::create();

$app->group('/users', function (RouteCollectorProxy $group) {
    $group->post('', [UserController::class, 'create']);
    $group->group('',function (RouteCollectorProxy $group){
        $group->get('', [UserController::class, 'all_users']);
        $group->get('/your-chatrooms', [UserController::class,'your_chatrooms']);
        $group->get('/{id}',[UserController::class,'show']);
    })->addMiddleware(new Authentication());
});


