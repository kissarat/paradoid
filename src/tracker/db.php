<?php
require './config.php';

class Database {
    private $pdo;

    public function connect() {
        if (!$this->pdo) {
            $this->pdo = new PDO(
                config_get('DB_DSN'),
                config_get('DB_USER'),
                config_get('DB_PASSWORD'),
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        }
        return $this->pdo;
    }

    public function prepare(string $sql) {
        return $this->connect()->prepare($sql);
    }

    public function bindParams(PDOStatement $statement, array $params = [])
    {
        foreach($params as $key => $value) {
            $statement->bindValue(":$key", $value);
        }
    }

    public function execute(PDOStatement $statement, array $params = []) {
        $this->bindParams($statement, $params);
        if ($statement->execute()) {
            return $statement;
        }
    }
    
    public function query(string $sql, array $params = []) {
        $statement = $this->prepare($sql);
        return $this->execute($statement, $params);
    }
}
