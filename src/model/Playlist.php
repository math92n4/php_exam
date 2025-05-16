<?php

require_once "DefaultModel.php";

class Playlist extends DefaultModel {

    public function getAll(): bool|array {
        $sql = "
            SELECT * FROM playlist;
        ";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll();
    }

    public function search(string $searchTerm) {
        $sql = "
            SELECT * FROM playlist WHERE Name LIKE ?;
        ";

        $stmt = $this->execute($sql, ['%' . $searchTerm . '%']);
        return $stmt->fetchAll();
    }

    public function getById(int $id) {
        $sql = "
            SELECT p.PlaylistId, p.Name as PlaylistName,
            t.TrackId, t.Name as TrackName, t.AlbumId, t.MediaTypeId, t.GenreId,
            t.Composer, t.MilliSeconds, t.Bytes, t.UnitPrice 
            FROM playlist p
            LEFT JOIN playlisttrack pt ON p.PlaylistId = pt.PlaylistId
            LEFT JOIN track t ON pt.TrackId = t.TrackId
            WHERE p.PlaylistId = ?;
        ";
        $stmt = $this->execute($sql, [$id]);
        $data = $stmt->fetchAll();

        if(!$data) {
            return null;
        }

        if ($data[0]['TrackId'] === null) {
            return [
                "PlaylistId" => $data[0]["PlaylistId"],
                "PlaylistName" => $data[0]["PlaylistName"]
            ];
        }
        return $data;
    }

    public function add(string $name) {
        $sql = "
            INSERT INTO playlist(Name)
            VALUES (?);
        ";

        $this->execute($sql, [$name]);
        return $this->db->lastInsertId();
    }

    public function addTrack(int $playlistId, int $trackId) {
        $sql = "
            INSERT INTO playlisttrack(PlaylistId, TrackId)
            VALUES(?, ?);
        ";

        $stmt = $this->execute($sql, [$playlistId, $trackId]);

        return $stmt->rowCount() > 0;
    }

    public function deleteTrack(int $playlistId, int $trackId) {
        $sql = "
            DELETE FROM playlisttrack WHERE PlaylistId = ? AND TrackId = ?; 
        ";

        $stmt = $this->execute($sql, [$playlistId, $trackId]);

        return $stmt->rowCount() > 0;
    }

    private function hasTracks(int $id) {
        $sql = "
            SELECT COUNT(*) as count FROM playlisttrack WHERE PlaylistId = ?;
        ";

        $stmt = $this->execute($sql, [$id]);
        $result = $stmt->fetch();

        return $result['count'] > 0;
    }
    
    public function delete(int $id) {
        if($this->hasTracks($id)) {
            return false;
        }

        $sql = "
            DELETE FROM playlist WHERE PlaylistId = ?;
        ";

        $stmt = $this->execute($sql, [$id]);
        return $stmt->rowCount() > 0;
    }
}