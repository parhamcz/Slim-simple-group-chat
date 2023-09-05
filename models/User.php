<?php

namespace models;
require __DIR__ . '/BaseModel.php';

class User extends BaseModel
{
    public function chatrooms($user)
    {
        try{
            $sql = "
                     SELECT chatrooms.name
                     FROM chatroom_user
                     JOIN chatrooms  ON chatrooms.id = chatroom_user.chatroom_id
                     WHERE chatroom_user.user_id = (:id);
                    ";
            $stmt = $this->db->prepare($sql);
            $id = $user->id;
            $stmt->bindParam(':id',$id,\PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }catch (\PDOException $e){
            return $e->getMessage();
        }

    }
}