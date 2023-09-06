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
    /**
     * returns all the messages from a given chatroom
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function all_messages(Request $request, Response $response): Response
    {
        try {
            $db = new DB('sqlite:slim-chatroom.db');
            $message = new Message($db);
            $user_instance = new User($db);
            $messages = $message->getAll('chatroom_messages');
            $data = array_map(function ($message) use ($user_instance) {
                $user = $user_instance->find('users',$message->user_id);
                return [
                    'from' =>$user->display_name,
                    'message' => $message->text
                ];
            }, $messages);
            $response->getBody()->write(json_encode($this->result(
                true,
                "Chatroom's messages fetched successfully",
                $data
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

    /**
     * sends a message to the given chatroom
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function send(Request $request, Response $response, array $args): Response
    {
        try {
            $username = $request->getHeaderLine('username');
            $text = $request->getParsedBody()['message'];
            $db = new DB('sqlite:slim-chatroom.db');

            $user_instance = new User($db);
            $chatroom_instance = new Chatroom($db);
            $message = new Message($db);

            $chatroom = $chatroom_instance->find('chatrooms', $args['id']);
            $user = $user_instance->findByCol('users', 'username', $username);
            $messages = $message->send($user, $text, $chatroom);

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