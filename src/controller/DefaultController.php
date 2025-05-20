<?php

require_once __DIR__ . '/../core/Logger.php';

class DefaultController {

    protected $request;

    public function __construct($request) {
        Logger::log("Request: {$_SERVER['REQUEST_METHOD']} {$_SERVER['REQUEST_URI']}");
        $this->request = $request;
    }

    protected function response($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        return $data;
    }

}