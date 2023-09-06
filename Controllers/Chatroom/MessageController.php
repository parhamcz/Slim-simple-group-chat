<?php

namespace Controllers\Chatroom;

use Controllers\Controller;
use database\DB;
use models\Chatroom;
use models\Message;
use models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MessageController extends Controller
{
    public function all_messages(Request $request, Response $response): Response
    {
        try {
            $db = new DB('sqlite:slim-chatroom.db');
            $message = new Message($db);
            $messages = $message->getAll('chatroom_messages');
            $response->getBody()->write(json_encode($this->result(
                true,
                "Chatroom's messages fetched successfully",
                $messages,
            )));
        } catch (\PDOException $exception) {
            $response->getBody()->write(($this->result(
                false,
                "Error in fetching chatroom's messages",
                [],
                500
            )));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function send(Request $request, Response $response, array $args): Response
    {
        try {
            $username = $request->getHeader('username')[0];
            $message = $request->getParsedBody()['message'];
            $db = new DB('sqlite:slim-chatroom.db');

            $user_instance = new User($db);
            $chatroom_instance = new Chatroom($db);
            $message = new Message($db);

            $chatroom = $chatroom_instance->find('chatrooms', $args['id']);
            $user = $user_instance->findByCol('users', 'username', $username);
            $messages = $message->send($user, $message, $chatroom);

            $response->getBody()->write(json_encode($this->result(
                true,
                "Message sent successfully",
                [
                    'message' => $messages,
                    'user' => $user,
                    'chatroom' => $chatroom
                ],
            )));
        } catch (\PDOException $exception) {
            $response->getBody()->write(($this->result(
                false,
                "Error in sending message",
                [],
                500
            )));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}