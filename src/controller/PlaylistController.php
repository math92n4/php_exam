<?php


class PlaylistController extends DefaultController {

    private $playlist;

    public function __construct($requst) {
        parent::__construct($requst);
        $this->playlist = new Playlist();
    }

    public function getAll() {
        try {
            $searchTerm = $this->request->getQueryParam('s');

            if($searchTerm) {
                $playlists = $this->playlist->search($searchTerm);
            } else {
                $playlists = $this->playlist->getAll();
            }

            if(!$playlists) {
                return $this->response(['error' => 'No playlist found'], 404);
            }

            return $this->response($playlists);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

    public function getById(int $id) {
        try {
            $rows = $this->playlist->getById($id);

            if(!$rows) {
                return $this->response(['error' => 'Playlist not found'], 404);
            }

            if (!isset($rows[0]['TrackId'])) {
                return $this->response($rows);
            }

            $playlist = [
                "PlaylistId" => $rows[0]["PlaylistId"],
                "PlaylistName" => $rows[0]["PlaylistName"],
                "Tracks" => []
            ];

            foreach ($rows as $row) {
                if($row["TrackId"] !== null) {
                    $playlist["Tracks"][] = [
                    "TrackId" => $row["TrackId"],
                    "TrackName" => $row["TrackName"],
                    "AlbumId" => $row["AlbumId"],
                    "MediaTypeId" => $row["MediaTypeId"],
                    "GenreId" => $row["GenreId"],
                    "Composer" => $row["Composer"],
                    "MilliSeconds" => $row["MilliSeconds"],
                    "Bytes" => $row["Bytes"],
                    "UnitPrice" => $row["UnitPrice"],
                    ];
                }
                
            }
            return $this->response($playlist);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

    public function add() {
        try {
            $name = $this->request->body()['name'] ?? null;

            if(!isset($name)) {
                return $this->response(['error' => 'Missing name field']);
            }

            $name = strip_tags(trim($name));

            $playlistId = $this->playlist->add($name);

            if(!$playlistId) {
                return $this->response(['error' => 'Failed to create playlist'], 500);
            }

            $createdPlaylist = $this->playlist->getById($playlistId);
            return $this->response($createdPlaylist, 201);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        

    }

    public function addTrack(int $id) {
        try {
            $trackId = $this->request->body()['track_id'] ?? null;

            if(!isset($trackId)) {
                return $this->response(['error' => 'Missing track_id']);
            }

            $success = $this->playlist->addTrack($id, $trackId);

            if (!$success) {
                return $this->response(['error' => 'Failed to add track to playlist'], 500);
            }

            return $this->response(['message' => 'Track added successfully',
                                    'PlaylistId' => $id,
                                    'TrackId' => $trackId], 201);

        } catch(Exception $e) {
            return $this->response(['error' => "Failed to add track to playlist"], 500);
        }
        

    }

    public function deleteTrack(int $playlistId, int $trackId) {
        try {
            $success = $this->playlist->deleteTrack($playlistId, $trackId);
            if(!$success) {
                return $this->response(['error' => 'Error deleting track'], 500);
            }
            return $this->response(['message' => 'TrackId: ' . $trackId . ' deleted from PlaylistId: ' . $playlistId], 200);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

    public function delete(int $id) {
        try {
            $success = $this->playlist->delete($id);
            if(!$success) {
                return $this->response(['error' => 'Error deleting playlist'], 500);
            }
            return $this->response(['message' => 'PlaylistId: ' . $id . ' deleted'], 200);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

}