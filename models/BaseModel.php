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
        try {
            $stmt = $this->db->query("SELECT * FROM $table");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return $e->getMessage();
        }

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
            return $values;
        } catch (\PDOException $e) {
            return $e->getMessage();
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
            return $e->getMessage();
        }
    }
}