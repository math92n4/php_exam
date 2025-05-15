<?php

require_once 'DefaultController.php';
require_once 'src/model/Track.php';

class TrackController extends DefaultController {

    protected $track;

    public function __construct($request) {
        parent::__construct($request);
        $this->track = new Track();
    }

    public function search(string $searchTerm) {
        
    }

    public function getById(int $id) {

    }

    public function getByComposer(string $composer) {

    }

    public function add() {

    }

    public function put(int $id) {

    }

    public function delete(int $id) {

    }

}