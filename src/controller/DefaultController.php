<?php


class DefaultController {

    protected $request;

    public function __construct($request) {
        $this->request = $request;
    }

    protected function response($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        return $data;
    }

}