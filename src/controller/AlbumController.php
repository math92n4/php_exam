<?php

require_once 'DefaultController.php';
require_once 'src/model/Album.php';

class AlbumController extends DefaultController {

    public function getAll() {
        $album = new Album();
        $albums = $album->getAll();
        return $this->response($albums);
    }

    /*
    public function store($request) {
        $data = $request->body();
        // Save $data to DB
        return $this->response(['message' => 'Album created'], 201);
    }
    */
}