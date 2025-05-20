<?php

require_once 'DefaultController.php';

class DocsController extends DefaultController {

    public function __construct($requst) {
        parent::__construct($requst);
    }

    // DOESNT WORK YES
    public function getDocs() {
        $file = __DIR__ . '/../../docs.json';
        $json = file_get_contents($file);
        return $this->response($json);
    }
}