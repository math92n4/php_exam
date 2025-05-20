<?php

require_once "DefaultModel.php";

class Track extends DefaultModel {


    public function search(string $searchTerm): bool|array {
        $sql = "
            SELECT t.TrackId, t.Name as TrackName, t.Composer, t.Milliseconds, t.Bytes, t.UnitPrice,
            m.MediaTypeId, m.Name as MediaName, g.GenreId, g.Name from track t
            INNER JOIN mediatype m on t.MediaTypeId = m.MediaTypeId
            INNER JOIN genre g on t.GenreId = g.GenreId
            WHERE t.Name LIKE ?;
        ";
        $stmt = $this->execute($sql, ['%' . $searchTerm . '%']);
        return $stmt->fetchAll();
    }

    public function getById(int $id) {
        $sql = "
            SELECT * FROM track WHERE TrackId = ?;
        ";
        $stmt = $this->execute($sql, [$id]);
        return $stmt->fetch();
    }

    public function getByComposer(string $searchTerm) {
        $sql = "
            SELECT * FROM track WHERE composer LIKE ?;
        ";
        $stmt = $this->execute($sql, ['%' . $searchTerm . '%']);
        return $stmt->fetchAll();
    }

    public function add(array $track) {
        $sql = "
            INSERT INTO track (Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?);
        ";
        $this->execute($sql, $track);
        return $this->db->lastInsertId();
    }

    public function put(int $id, array $track) {
        if(empty($track)) {
            return false;
        }

        $dbColumns = [];
        $values = [];

        foreach($track as $column => $value) {
            $dbColumns[] = "$column = ?";
            $values[] = $value;
        }
        $values[] = $id;

        $sql = "
            UPDATE track SET " . implode(", ", $dbColumns) . " WHERE TrackId = ?;
        ";
        
        $stmt = $this->execute($sql, $values);

        return $stmt->rowCount() > 0;
    }

    private function isOnPlaylist(int $id) {
        $sql = "
            SELECT COUNT(*) as count FROM playlisttrack WHERE TrackId = ?;
        ";
        $stmt = $this->execute($sql, [$id]);
        $result = $stmt->fetch();

        return $result['count'] > 0;
    }

    public function delete(int $id): bool {
        if($this->isOnPlaylist($id)) {
            return false;
        }

        $sql = "
            DELETE FROM track WHERE TrackId = ?;
        ";
        $stmt = $this->execute($sql, [$id]);
        return $stmt->rowCount() > 0;
    }
}