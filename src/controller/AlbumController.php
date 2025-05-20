<?php

require_once 'DefaultController.php';
require_once 'src/model/Album.php';

class AlbumController extends DefaultController {

    private $album;

    public function __construct($request) {
        parent::__construct($request);
        $this->album = new Album();
    }

    public function getAll() {
        try {
            $searchTerm = $this->request->getQueryParam('s');

            if($searchTerm) {
                $albums = $this->album->search($searchTerm);
            } else {
                $albums = $this->album->getAll();
            }

            if(!$albums) {
                return $this->response(['error' => 'No albums found'], 404);
            }

            return $this->response($albums);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

    public function getById(int $id) {
        try {
            $album = $this->album->getById($id);
            if(!$album) {
                return $this->response(['error' => 'Album not found'], 404);
            }
            return $this->response($album);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

    public function getTracksByAlbumId(int $id) {
        try {
            $album = $this->album->getTracksByAlbumId($id);
            if(!$album) {
                return $this->response(['error' => 'Album not found'], 404);
            }
            return $this->response($album);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

    public function add() {
        try {
            $data = $this->request->body();

            if (!isset($data['title'], $data['artist_id'])) {
                return $this->response(['error' => 'Missing required fields: title or artist_id'], 400);
            }

            $albumId = $this->album->add($data);
            $createdAlbum = $this->album->getById($albumId);

            return $this->response($createdAlbum, 201);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

    public function put(int $id) {
        try {
            $data = $this->request->body();

            if (empty($data['title']) && empty($data['artist_id'])) {
                return $this->response(['error' => 'Provide either title or artist_id'], 400);
            }

            $success = $this->album->put($id, $data);

            if (!$success) {
                return $this->response(['error' => 'Album not found or not updated'], 404);
            }

            $updatedAlbum = $this->album->getById($id);
            return $this->response($updatedAlbum);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

    public function delete(int $id) {
        try {
            $success = $this->album->delete($id);
            if(!$success) {
                return $this->response(['error' => 'Could not delete'], 400);
            }

            return $this->response(['data' => "Album id: " . $id . " deleted"], 200);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
    }

    
}