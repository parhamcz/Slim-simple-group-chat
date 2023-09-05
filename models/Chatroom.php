<?php

namespace models;
use models\BaseModel;

class Chatroom extends BaseModel
{
    public function users($chatroom)
    {
        try{
            $sql = "
                     SELECT users.display_name,users.username
                     FROM chatroom_user
                     JOIN users ON users.id = chatroom_user.user_id
                     WHERE chatroom_user.chatroom_id = (:id);
                    ";
            $stmt = $this->db->prepare($sql);
            $id = $chatroom->id;
            $stmt->bindParam(':id',$id,\PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }catch (\PDOException $e){
            return $e->getMessage();
        }
    }

}