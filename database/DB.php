<?php

namespace database;

class DB extends \PDO
{
    public function run(string $sql, array $params = [])
    {
        try {
            // Prepare the SQL query
            $stmt = $this->prepare($sql);

            // Bind parameters if provided
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }

            // Execute the query
            $stmt->execute();

            // Return the statement object (useful for SELECT queries)
            return $stmt;
        } catch (\PDOException $e) {
            $result = [
                'status' => false,
                'message' => $e->getMessage()
            ];
            return json_encode($result);
        }

    }
}