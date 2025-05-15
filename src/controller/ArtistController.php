<?php

require_once 'DefaultController.php';
require_once 'src/model/Artist.php';

class ArtistController extends DefaultController {

    protected $artist;

    public function __construct($requst) {
        parent::__construct($requst);
        $this->artist = new Artist();
    }

    public function getAll(string $searchTerm = null) {

        if($searchTerm) {
            $artists = $this->artist->search($searchTerm);
        } else {
            $artists = $this->artist->getAll();
        }

        if(!$artists) {
            return $this->response(['error' => 'No artists found'], 404);
        }

        return $this->response($artists);
    }

    public function getById(int $id) {
        $artist = $this->artist->getById($id);
        if(empty($artist)) {
            return $this->response(['error' => 'Artist not found'], 404);
        }
        return $this->response($artist);
    }

    public function getAlbumsByArtistId(int $id) {
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
    }

    public function add() {
        $data = $this->request->body();

        if(!isset($data['name'])) {
            return $this->response(['error' => 'Missing name field']);
        }

        $artistId = $this->artist->add($data['name']);
        $createdArtist = $this->artist->getById($artistId);

        return $this->response($createdArtist, 201);
    }

    public function delete(int $id) {
        $succes = $this->artist->delete($id);
        if(!$succes) {
            return $this->response(['error' => 'Could not delete'], 400);
        }

        return $this->response(['data' => "Artist id: " . $id . " deleted"], 200);
    }

}