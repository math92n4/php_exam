<?php

require_once 'DefaultController.php';
require_once 'src/model/Genre.php';

class GenreController extends DefaultController {

    protected $genre;

    public function __construct($request) {
        parent::__construct($request);
        $this->genre = new Genre();
    }

    public function getAll() {

        $genres = $this->genre->getAll();

        if(!$genres) {
            return $this->response(['error' => 'No genres found'], 404);
        }

        return $this->response($genres);
    }

}