<?php


class Album extends DefaultModel {

    public function getAll(): bool|array {
        $sql = "
            SELECT a.AlbumId, a.Title as AlbumTitle, ar.ArtistId, ar.Name as ArtistName from Album a
            INNER JOIN artist ar on a.ArtistId = ar.ArtistId;
        ";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll();
    }

    public function search(string $searchTerm): bool|array {
        $sql = "
            SELECT a.AlbumId, a.Title as AlbumTitle, ar.ArtistId, ar.Name as ArtistName from Album a
            INNER JOIN artist ar ON a.ArtistId = ar.ArtistId
            WHERE a.Title LIKE ?;
        ";
        $stmt = $this->execute($sql, ['%' . $searchTerm . '%']);
        return $stmt->fetchAll();
    }

    public function getById(int $id): bool|array {
        $sql = "
            SELECT a.AlbumId, a.Title as AlbumTitle, ar.ArtistId, ar.Name as ArtistName from Album a 
            INNER JOIN Artist ar on a.ArtistId = ar.ArtistId
            WHERE AlbumId = ?;
        ";
        $stmt = $this->execute($sql, [$id]);
        return $stmt->fetch();
    }

    public function getTracksByAlbumId(int $id): bool|array {
        $sql = "
            SELECT t.TrackId, t.Name as TrackName, t.Composer, t.Milliseconds, t.Bytes, t.UnitPrice,
            mt.Name as MediaType, g.Name as Genre FROM Track t
            INNER JOIN MediaType mt ON t.MediaTypeId = mt.MediaTypeId
            INNER JOIN Genre g on t.GenreId = g.GenreId
            WHERE t.AlbumId = ?;
        ";
        $stmt = $this->execute($sql, [$id]);
        return $stmt->fetchAll();
    }

    public function add(array $album) {
        $sql = "
            INSERT INTO Album(Title, ArtistId)
            VALUES(?, ?)
        ";

        $this->execute($sql, [$album["title"], $album["artist_id"]]);
        return $this->db->lastInsertId();
    }

    public function put(int $id, array $album) {
        $fields = [];
        $params = [];

        if (isset($album['title'])) {
            $fields[] = 'Title = ?';
            $params[] = $album['title'];
        }

        if (isset($album['artist_id'])) {
            $fields[] = 'ArtistId = ?';
            $params[] = $album['artist_id'];
        }

        if (empty($fields)) {
            return false;
        }

        $params[] = $id;
        $sql = "UPDATE Album SET " . implode(', ', $fields) . " WHERE AlbumId = ?";

        $stmt = $this->execute($sql, $params);
        return $stmt->rowCount() > 0;
    }

    private function hasTracks(int $id): bool {
        $sql = "SELECT COUNT(*) as count FROM Track WHERE AlbumId = ?";
        $stmt = $this->execute($sql, [$id]);
        $result = $stmt->fetch();

        return $result['count'] > 0;
    }

    public function delete(int $id): bool {
        if($this->hasTracks($id)) {
            return false;
        }

        $sql = "DELETE FROM Album WHERE AlbumId = ?";
        $stmt = $this->execute($sql, [$id]);
        return $stmt->rowCount() > 0;
    }
}