<?php


class ArtistController extends DefaultController {

    private $artist;

    public function __construct($requst) {
        parent::__construct($requst);
        $this->artist = new Artist();
    }

    public function getAll() {
        try {
            $searchTerm = $this->request->getQueryParam('s');

            if($searchTerm) {
                $artists = $this->artist->search($searchTerm);
            } else {
                $artists = $this->artist->getAll();
            }

            if(!$artists) {
                return $this->response(['error' => 'No artists found'], 404);
            }

            return $this->response($artists);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

    public function getById(int $id) {
        try {
            $artist = $this->artist->getById($id);

            if(empty($artist)) {
                return $this->response(['error' => 'Artist not found'], 404);
            }

            return $this->response($artist);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

    public function getAlbumsByArtistId(int $id) {
        try {
            $rows = $this->artist->getAlbumsByArtistId($id);
            if(empty($rows)) {
                return $this->response(['error' => 'Artist not found'], 404);
            }

            $artist = [
                "ArtistId" => $rows[0]['ArtistId'],
                "ArtistName" => $rows[0]['ArtistName'],
                "Albums" => []
            ];

            foreach ($rows as $row) {
                $artist['Albums'][] = [
                    "AlbumId" => $row['AlbumId'],
                    "AlbumTitle" => $row['AlbumTitle']
                ];
            }

            return $this->response($artist);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

    public function add() {
        try {
            $data = $this->request->body();

            if(!isset($data['name'])) {
                return $this->response(['error' => 'Missing name field']);
            }

            $artistId = $this->artist->add($data['name']);
            $createdArtist = $this->artist->getById($artistId);

            return $this->response($createdArtist, 201);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

    public function delete(int $id) {
        try {
            $succes = $this->artist->delete($id);
            if(!$succes) {
                return $this->response(['error' => 'Could not delete'], 400);
            }

            return $this->response(['data' => "Artist id: " . $id . " deleted"], 200);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

}