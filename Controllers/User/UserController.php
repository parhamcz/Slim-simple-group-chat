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
                [],
                500
            )));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function create(Request $request, Response $response)
    {
        try {
            $inputs = $request->getParsedBody();
            $db = new DB('sqlite:slim-chatroom.db');
            $user = new User($db);
            $data = [
                'display_name' => $inputs['username'],
                'username' => $inputs['display_name']
            ];
            $users = $user->create('users', $data);
            $response->getBody()->write(json_encode(
                $this->result(
                    true,
                    'User created successfully',
                    $data
                )
            ));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\PDOException $e) {
            $response->getBody()->write(
                json_encode(
                    $this->result(
                        false,
                        'Error in creatinf user',
                        [],
                        500
                    )
                )
            );
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}