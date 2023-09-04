<?php

namespace models;

class BaseModel
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
    }

    public function getAll($table)
    {
        $stmt = $this->db->query("SELECT * FROM $table");
        return $stmt->fetchAll();
    }

    public function create(string $table, array $values)
    {
        try {
            $fields = implode(', ', array_keys($values));
            $placeholders = ':' . implode(', :', array_keys($values));

            $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";

            $stmt = $this->db->prepare($sql);

            foreach ($values as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }

            $stmt->execute();
            $result = [
                'status' => true,
                'message' => 'Insertion Successful'
            ];
            return $result;
        } catch (\PDOException $e) {
            $result = [
                'status' => false,
                'message' => 'Insertion Failed' . $e->getMessage()
            ];
            return $result;
        }
    }

    public function find($table, $id)
    {
        try {
            $sql = "SELECT * FROM $table WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            return $stmt->fetch();
        } catch (\PDOException $e) {
            // Handle the error (e.g., log it, display an error message)
            echo 'Error finding record: ' . $e->getMessage();
            return false; // Or handle the error in a way that suits your application
        }
    }
}