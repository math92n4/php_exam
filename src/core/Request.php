<?php


class Request {

    public function getPath() {

        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uriPath = parse_url($uri, PHP_URL_PATH);

        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

        if (strpos($uriPath, $basePath) === 0) {
            $uriPath = substr($uriPath, strlen($basePath));
        }

        $uriPath = '/' . ltrim($uriPath, '/');

        return rtrim($uriPath, '/') ?: '/';
    }

    public function method() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function body() {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function getQueryParam($key) {
        return $_GET[$key] ?? null;
    }
}