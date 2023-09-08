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
            return $this->write($response, $this->result(
                true,
                'Users fetched successfully',
                $users,
            ));
        } catch (\PDOException $exception) {
            return $this->write($response, $this->result(
                false,
                'Error in fetching users',
                [],
                500
            ));
        }
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
                'display_name' => $inputs['display_name'],
                'username' => $inputs['username']
            ];
            $result = $user->create('users', $data);
            $this->write($response, $this->result(
                true,
                'User created successfully',
                $result
            ));
        } catch (\Exception $e) {
            return $this->write($response, $this->result(
                false,
                'Error in creating user',
                [$e->getMessage()],
                500
            ));
        }
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
            if(!$data){
                return $this->write($response, $this->notFound("User not found"));
            }
            return $this->write($response, $this->result(
                true,
                'User fetched successfully',
                $data
            ));
        } catch (\Exception $exception) {
            return $this->write($response, $this->result(
                false,
                'Error in creating user',
                [],
                500
            ));
        }
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
            $username = $request->getHeaderLine('username');
            $db = new DB('sqlite:slim-chatroom.db');
            $user = new User($db);
            $result = $user->findByCol('users', 'username', $username);
            if(!$result){
                return $this->write($response, $this->notFound("User not found"));
            }
            $chatrooms = $user->chatrooms($result);
           return $this->write($response, $this->result(
                true,
                "User's chatrooms fetched successfully",
                $chatrooms
            ));
        } catch (\Exception $e) {
            return $this->write($response, $this->result(
                false,
                "Error in fetching user's chatrooms",
                [],
                500
            ));
        }
    }
}