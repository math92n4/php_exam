<?php

require_once "DefaultModel.php";

class MediaType extends DefaultModel {

    public function getAll(): bool|array {
        $sql = "
            SELECT * FROM mediatype;
        ";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll();
    }

}