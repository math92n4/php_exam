<?php



class Genre extends DefaultModel {

    public function getAll(): bool|array {
        $sql = "
            SELECT * from Genre;
        ";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll();
    }
}