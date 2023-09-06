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
    /** returns all the users.
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function all_users(Request $request, Response $response): Response
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
        } catch (\PDOException $exception) {
            $response->getBody()->write(($this->result(
                false,
                'Error in fetching users',
                [],
                500
            )));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * creates a user.
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function create(Request $request, Response $response): Response
    {
        try {
            $inputs = $request->getParsedBody();
            $db = new DB('sqlite:slim-chatroom.db');
            $user = new User($db);
            $data = [
                'display_name' => $inputs['username'],
                'username' => $inputs['display_name']
            ];
            $result = $user->create('users', $data);
            $response->getBody()->write(json_encode(
                $this->result(
                    true,
                    'User created successfully',
                    $result
                )
            ));
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(
                $this->result(
                    false,
                    'Error in creatinf user',
                    [],
                    500
                )
            ));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * shows a user's info.
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        try {
            $db = new DB('sqlite:slim-chatroom.db');
            $user = new User($db);
            $data = $user->find('users', $args['id']);
            $response->getBody()->write(json_encode(
                $this->result(
                    true,
                    'User fetched successfully',
                    $data
                )
            ));
        } catch (\Exception $exception) {
            $response->getBody()->write(json_encode(
                $this->result(
                    false,
                    'Error in creating user',
                    [],
                    500
                )
            ));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * gets the username from header as a method of authentication.
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function your_chatrooms(Request $request, Response $response): Response
    {
        try {
            $username = $request->getHeader('username')[0];
            $db = new DB('sqlite:slim-chatroom.db');
            $user = new User($db);
            $result = $user->findByCol('users', 'username', $username);
            $chatrooms = $user->chatrooms($result);
            $response->getBody()->write(json_encode(
                $this->result(
                    true,
                    "User's chatrooms fetched successfully",
                    $chatrooms
                )
            ));
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(
                $this->result(
                    false,
                    "Error in fetching user's chatrooms",
                    [],
                    500
                )
            ));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}