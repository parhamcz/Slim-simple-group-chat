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

    public function result(bool $status, string $message, $data, $code = 200): array
    {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'status code' => $code
        ];
    }

    public function getAll($table)
    {
        try {
            $stmt = $this->db->query("SELECT * FROM $table");
            return $this->result(
                true,
                'users fetched successfully',
                $stmt->fetchAll()
            );
        } catch (\PDOException $e) {
            return $this->result(
                false,
                'Error in fetching all users',
                $e->getMessage(),
                500
            );
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
            return $this->result(
                true,
                'Insertion Successful',
                '');
        } catch (\PDOException $e) {
            return $this->result(
                false,
                'Insertion Failed',
                $e->getMessage(),
                500
            );
        }
    }

    public function find($table, $id)
    {
        try {
            $sql = "SELECT * FROM $table WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $this->result(
                true,
                'user found successfully',
                $stmt->fetch()
            );
        } catch (\PDOException $e) {
            return $this->result(
                false,
                'Error in finding the user',
                $e->getMessage(),
                500
            );
        }
    }
}