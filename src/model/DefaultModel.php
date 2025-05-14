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
        $stmt->execute($params);
        return $stmt;
    }

}
