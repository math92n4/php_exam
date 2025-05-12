<?php


class DefaultController {

    protected function response($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        return $data;
    }

}