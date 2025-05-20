<?php

require_once __DIR__ . "/../db/Database.php";

class DefaultModel {

    protected PDO $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    protected function execute($sql, $params = []) {
        $stmt = $this->db->prepare($sql);

        if(!$stmt) {
            $error = $this->db->errorInfo();
            throw new Exception("Could not prepare statement: " . $error);
        }

        if(!$stmt->execute($params)) {
            $error = $this->db->errorInfo();
            throw new Exception("Could no execute statement: " . $error);
        }

        return $stmt;
    }

}
