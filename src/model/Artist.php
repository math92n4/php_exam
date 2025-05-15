<?php

require_once "DefaultModel.php";

class Artist extends DefaultModel {

    public function getAll(): bool|array {
        $sql = "SELECT ArtistId, Name as AristName from artist;";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll();
    }

    public function search(string $searchTerm): bool|array {
        $sql = "
            SELECT ArtistId, Name as ArtistName from artist
            WHERE Name LIKE ?;
        ";
        $stmt = $this->execute($sql, ['%' . $searchTerm . '%']);
        return $stmt->fetchAll();
    }

    public function getById(int $id): bool|array {
        $sql = "
            SELECT ArtistId, Name as ArtistName from artist
            WHERE ArtistId = ?;
        ";
        $stmt = $this->execute($sql, [$id]);
        return $stmt->fetch();
    }

    public function getAlbumsByArtistId(int $id): bool|array {
        $sql = "
            SELECT a.ArtistId, a.Name as ArtistName, ar.AlbumId, ar.Title as AlbumTitle
            FROM artist a
            INNER JOIN album ar on a.ArtistId = ar.ArtistId
            WHERE a.ArtistId = ?;
        ";
        $stmt = $this->execute($sql, [$id]);
        return $stmt->fetchAll();
    }

    public function add(string $name) {
        $sql = "
            INSERT INTO artist(Name)
            VALUES(?)
        ";

        $this->execute($sql, [$name]);
        return $this->db->lastInsertId();
    }

    private function hasAlbums(int $id): bool {
        $sql = "SELECT COUNT(*) as count FROM album WHERE ArtistId = ?";
        $stmt = $this->execute($sql, [$id]);
        $result = $stmt->fetch();

        return $result['count'] > 0;
    }

    public function delete(int $id): bool {
        if($this->hasAlbums($id)) {
            return false;
        }

        $sql = "DELETE FROM artist WHERE ArtistId = ?";
        $stmt = $this->execute($sql, [$id]);
        return $stmt->rowCount() > 0;
    }

}