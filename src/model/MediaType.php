<?php



class MediaType extends DefaultModel {

    public function getAll(): bool|array {
        $sql = "
            SELECT * FROM MediaType;
        ";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll();
    }

}