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
            return $this->write($response, $this->result(
                true,
                'Chatrooms fetched successfully',
                $chatrooms,
            ));
        } catch (\PDOException $exception) {
            return $this->write($response, $this->result(
                false,
                'Error in fetching chatrooms',
                [],
                500
            ));
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

            $username = $request->getHeaderLine('username');
            $inputs = $request->getParsedBody();
            $db = new DB('sqlite:slim-chatroom.db');

            $user_instance = new User($db);
            $user = $user_instance->findByCol('users', 'username', $username);
            $chatroom_instance = new Chatroom($db);
            $data = [
                'name' => $inputs['name'],
                'description' => $inputs['description'] ?? ''
            ];
            $chatroom = $chatroom_instance->create('chatrooms', $data);
            $chatroom_instance->join($chatroom, $user);
            $chatroom_instance->setAdmin($chatroom, $user);
            return $this->write($response, $this->result(
                true,
                'chatroom created successfully',
                [
                    'chatroom' => $chatroom,
                    'users' => $user
                ]
            )
            );
        } catch (\Exception $e) {
            return $this->write($response, json_encode(
                $this->result(
                    false,
                    'Error in creating chatroom',
                    [],
                    500
                )
            ));
        }
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
            if (!empty($data['chatroom'])) {
                $data['users'] = $chatroom->users($data['chatroom']);
                return $this->write($response, $this->result(
                    true,
                    "Chatroom's info fetched successfully",
                    $data));
            }
            return $this->write($response, $this->notFound("Chatroom not found"));
        } catch (\Exception $exception) {
            return $this->write($response, $this->result(
                false,
                'Error in fetching chatroom',
                [],
                500
            )
            );
        }
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
            $username = $request->getHeaderLine('username');
            $db = new DB('sqlite:slim-chatroom.db');

            $user_instance = new User($db);
            $chatroom_instance = new Chatroom($db);

            $user = $user_instance->findByCol('users', 'username', $username);
            $chatroom = $chatroom_instance->find('chatrooms', $args['id']);

            if (!empty($chatroom)) {
                if ($chatroom_instance->join($chatroom, $user)) {
                    return $this->write($response, $this->result(
                        true,
                        "User joined the chatroom successfully",
                        [
                            'chatroom' => $chatroom,
                            'user' => $user
                        ]
                    ));
                }
                return $this->write($response, $this->result(
                    false,
                    "User is already joined",
                    [],
                )
                );
            }
            return $this->write($response, $this->notFound("Chatroom not found"));
        } catch (\Exception $e) {
            return $this->write($response, $this->result(
                false,
                "Error in joining the chatroom",
                [],
                500
            ));
        }
    }

    public function leave(Request $request, Response $response, array $args): Response
    {
        try {
            $username = $request->getHeaderLine('username');
            $db = new DB('sqlite:slim-chatroom.db');

            $user_instance = new User($db);
            $chatroom_instance = new Chatroom($db);

            $user = $user_instance->findByCol('users', 'username', $username);
            $chatroom = $chatroom_instance->find('chatrooms', $args['id']);

            if (!empty($chatroom)) {
                if ($chatroom_instance->leave($chatroom, $user) == 0) {
                    return $this->write($response, $this->result(
                        false,
                        "User is not in the chatroom",
                        [],
                        404
                    )
                    );
                }
                return $this->write($response, $this->result(
                    true,
                    "User left the chatroom successfully",
                    [
                        'chatroom' => $chatroom,
                        'user' => $user
                    ]
                ));
            }
            return $this->write($response, $this->notFound("Chatroom not found"));
        } catch (\Exception $e) {
            return $this->write($response, $this->result(
                false,
                "Error in leaving the chatroom",
                [],
                500
            ));
        }
    }

    public function set_admin(Request $request, Response $response, array $args): Response
    {
        try {
            $user_id = $args['user_id'];
            $chatroom_id = $args['chatroom_id'];
            $db = new DB('sqlite:slim-chatroom.db');

            $user_instance = new User($db);
            $chatroom_instance = new Chatroom($db);

            $chatroom = $chatroom_instance->find('chatrooms', $chatroom_id);
            $user = $user_instance->find('users', $user_id);
            if (!$chatroom) {
                return $this->write($response, $this->notFound("Chatroom not found"));
            }
            if (!$user) {
                return $this->write($response, $this->notFound("User not found"));
            }
            if ($chatroom_instance->setAdmin($chatroom, $user)) {
                return $this->write($response, $this->result(
                    true,
                    'User has become admin successfully',
                    [
                        'user' => $user,
                        'chatroom' => $chatroom
                    ]
                ));
            }
            return $this->write($response, $this->result(
                false,
                'User is not in the chatroom',
                [],
                400
            ));
        } catch (\Exception $e) {
            return $this->write($response, $this->result(
                false,
                'Error in assigning admin',
                [],
                500
            ));
        }
    }
}