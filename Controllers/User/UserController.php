<?php

namespace Controllers\User;

use Controllers\Controller;
use database\DB;
use PDO;
use models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends Controller
{
    public function all_users(Request $request, Response $response)
    {
        try {
            $db = new DB('sqlite:slim-chatroom.db');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $user = new User($db);
            $users = $user->getAll('users');
            $response->getBody()->write(json_encode($this->result(
                true,
                'Users fetched successfully',
                $users,
            )));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\PDOException $exception) {
            $response->getBody()->write(($this->result(
                false,
                'Error in fetching users',
                $exception->getMessage(),
                500
            )));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}