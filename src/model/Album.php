<?php


require_once __DIR__ . '/../db/Database.php';

class Album {

    public function getAll() {
        $db = new Database();
        $conn = $db->connect();
        $query = "SELECT * FROM album";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}