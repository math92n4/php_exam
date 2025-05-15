<?php

require_once 'DefaultController.php';
require_once 'src/model/Track.php';

class TrackController extends DefaultController {

    protected $track;

    public function __construct($request) {
        parent::__construct($request);
        $this->track = new Track();
    }

    public function search() {
        $searchTerm = $this->request->getQueryParam('s');
        if (!$searchTerm) {
            return $this->response(['error' => 'Search term is required'], 400);
        }

        $tracks = $this->track->search($searchTerm);

        if(!$tracks) {
            return $this->response(['error' => 'No tracks found'], 404);
        }

        return $this->response($tracks);
    }

    public function getById(int $id) {
        $track = $this->track->getById($id);
        if(!$track) {
            return $this->response(['error' => 'Track not found'], 404);
        }
        return $this->response($track);
    }

    public function getByComposer() {
        $searchTerm = $this->request->getQueryParam('composer');
        
        if (!$searchTerm) {
            return $this->response(['error' => 'Composer search is required'], 400);
        }

        $tracks = $this->track->getByComposer($searchTerm);

        if(!$tracks) {
            return $this->response(['error' => 'No tracks found'], 404);
        }

        return $this->response($tracks);
    }

    public function add() {
        $data = $this->request->body();

        $required = ['name', 'album_id', 'media_type_id', 'genre_id', 'composer', 'milliseconds', 'bytes', 'unit_price'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return $this->response(['error' => "$field is required"], 400);
            }
        }
        $trackId = $this->track->add([
            $data['name'],
            $data['album_id'],
            $data['media_type_id'],
            $data['genre_id'],
            $data['composer'],
            $data['milliseconds'],
            $data['bytes'],
            $data['unit_price']
        ]);

        if ($trackId) {
            $createdTrack = $this->track->getById($trackId);
            return $this->response($createdTrack, 201);
        } else {
            return $this->response(['error' => 'Failed to create track'], 500);
        }
    }

    public function put(int $id) {

    }

    public function delete(int $id) {
        $success = $this->track->delete($id);
        if(!$success) {
            return $this->response(['error' => 'Could not delete'], 400);
        }

        return $this->response(['data' => "Track id: " . $id . " deleted"], 200);
    }

}