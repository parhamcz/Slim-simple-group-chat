<?php

namespace models;

use models\BaseModel;

class Chatroom extends BaseModel
{
    public function users($chatroom)
    {
        $sql = "
                     SELECT users.display_name,users.username
                     FROM chatroom_user
                     JOIN users ON users.id = chatroom_user.user_id
                     WHERE chatroom_user.chatroom_id = (:id);
                    ";
        $stmt = $this->db->prepare($sql);
        $id = $chatroom->id;
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function join($chatroom, $user)
    {
        $sql = "SELECT COUNT(*) as count FROM chatroom_user WHERE user_id = :user_id AND chatroom_id = :chatroom_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user->id, \PDO::PARAM_INT);
        $stmt->bindParam(':chatroom_id', $chatroom->id, \PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result['count'] == 0) {
            // User is not in the chatroom, so insert them
            $sql = "INSERT INTO chatroom_user (user_id, chatroom_id) VALUES (:user_id, :chatroom_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $user->id, \PDO::PARAM_INT);
            $stmt->bindParam(':chatroom_id', $chatroom->id, \PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }
        return false;
    }

    public function leave($chatroom, $user)
    {
        $sql = "DELETE FROM chatroom_user WHERE user_id = :user_id AND chatroom_id = :chatroom_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':chatroom_id', $chatroom->id, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user->id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function isAdmin($chatroom, $user)
    {
        $sql = "SELECT is_admin FROM chatroom_user WHERE user_id = :user_id AND chatroom_id = :chatroom_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('user_id', $user->id, \PDO::PARAM_INT);
        $stmt->bindParam('chatroom_id', $chatroom->id, \PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->fetch()->is_admin == 0) {
            return false;
        }
        return true;
    }

    public function setAdmin($chatroom, $user)
    {
        $sql = "UPDATE chatroom_user
        SET is_admin = 1
        WHERE user_id = :user_id AND chatroom_id = :chatroom_id";
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam('user_id', $user->id, \PDO::PARAM_INT);
            $stmt->bindParam('chatroom_id', $chatroom->id, \PDO::PARAM_INT);

            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return false;
            }
            return true;
    }
}