<?php

namespace Controllers\Chatroom;

use Controllers\Controller;
use models\Chatroom;
use database\DB;
use PDO;
use models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ChatroomController extends Controller
{
    /** returns all the chatrooms.
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function all_chatrooms(Request $request, Response $response): Response
    {
        try {
            $db = new DB('sqlite:slim-chatroom.db');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $chatroom = new Chatroom($db);
            $chatrooms = $chatroom->getAll('chatrooms');
            $response->getBody()->write(json_encode($this->result(
                true,
                'Chatrooms fetched successfully',
                $chatrooms,
            )));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\PDOException $exception) {
            $response->getBody()->write(($this->result(
                false,
                'Error in fetching chatrooms',
                [],
                500
            )));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    /**
     * creates a chatroom.
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function create(Request $request, Response $response): Response
    {
        try {

            $username = $request->getHeader('username')[0];
            $inputs = $request->getParsedBody();
            $db = new DB('sqlite:slim-chatroom.db');

            $user_instance = new User($db);
            $user = $user_instance->findByCol('users', 'username', $username);
            $chatroom = new Chatroom($db);
            $data = [
                'name' => $inputs['name']
            ];
            $chatroom->create('chatrooms', $data);
            $created_chatroom = $chatroom->findByCol('chatrooms', 'name', $data['name']);
            $chatroom->join($created_chatroom, $user);
            $response->getBody()->write(json_encode(
                $this->result(
                    true,
                    'chatroom created successfully',
                    [
                        'chatroom' => $data,
                        'users' => $user
                    ]
                )
            ));
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(
                $this->result(
                    false,
                    'Error in creating chatroom',
                    [],
                    500
                )
            ));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * shows a Chatroom's info.
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        try {
            $db = new DB('sqlite:slim-chatroom.db');
            $chatroom = new Chatroom($db);
            $data['chatroom'] = $chatroom->find('chatrooms', $args['id']);
            $data['users'] = $chatroom->users($data['chatroom']);
            $response->getBody()->write(json_encode(
                $this->result(
                    true,
                    "Chatroom's info fetched successfully",
                    $data
                )
            ));
        } catch (\Exception $exception) {
            $response->getBody()->write(json_encode(
                $this->result(
                    false,
                    'Error in creating Chatroom',
                    [],
                    500
                )
            ));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * user can join the desired chatroom by chatroom's ID.
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function join(Request $request, Response $response, array $args): Response
    {
        try {
            $username = $request->getHeader('username')[0];
            $db = new DB('sqlite:slim-chatroom.db');
            $user_instance = new User($db);
            $chatroom_instance = new Chatroom($db);
            $user = $user_instance->findByCol('users', 'username', $username);
            $chatroom = $chatroom_instance->find('chatrooms', $args['id']);
            if($chatroom_instance->join($chatroom, $user)){
                $response->getBody()->write(json_encode(
                    $this->result(
                        true,
                        "User joined the chatroom successfully",
                        [
                            'chatroom' => $chatroom,
                            'user' => $user
                        ]
                    )
                ));
            }else{
                $response->getBody()->write(json_encode(
                    $this->result(
                        false,
                        "User is already joined",
                        [],
                    )
                ));
            }

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(
                $this->result(
                    false,
                    "Error in joining the chatroom",
                    [],
                    500
                )
            ));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function leave(Request $request, Response $response, array $args): Response
    {
        try {
            $username = $request->getHeader('username')[0];
            $db = new DB('sqlite:slim-chatroom.db');
            $user_instance = new User($db);
            $chatroom_instance = new Chatroom($db);
            $user = $user_instance->findByCol('users', 'username', $username);
            $chatroom = $chatroom_instance->find('chatrooms', $args['id']);
            if ($chatroom_instance->leave($chatroom, $user) == 0) {
                $response->getBody()->write(json_encode(
                    $this->result(
                        false,
                        "User is not in the chatroom",
                        [],
                        404
                    )
                ));
            } else {
                $response->getBody()->write(json_encode(
                    $this->result(
                        true,
                        "User left the chatroom successfully",
                        [
                            'chatroom' => $chatroom,
                            'user' => $user
                        ]
                    )
                ));
            }
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(
                $this->result(
                    false,
                    "Error in leaving the chatroom",
                    [],
                    500
                )
            ));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}