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
            $fields = implode(', ', array_keys($values));
            $placeholders = ':' . implode(', :', array_keys($values));

            $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";

            $stmt = $this->db->prepare($sql);

            foreach ($values as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }

            $stmt->execute();
            return $values;
    }

    public function find($table, $id)
    {
            $sql = "SELECT * FROM $table WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch();
    }

    public function findByCol(string $table, string $field, string|int $value)
    {
            $sql = "SELECT * FROM $table WHERE $field = :username";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':username', $value);
            $stmt->execute();
            return $stmt->fetch();
    }
}