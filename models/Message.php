<?php

namespace models;

class Message extends BaseModel
{
    public function send($user, $message, $chatroom)
    {
            return $this->create('chatroom_messages',[
                'user_id' => $user->id,
                'chatroom_id' => $chatroom->id,
                'text' => $message,
            ]);
    }
}