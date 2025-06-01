<?php


class Request {

    public function getPath() {

        // get the full request uri
        // eg /PHP_SOFT/php_exam/albums?s=for%20those
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // extract the actual request path without query params
        // eg /PHP_SOFT/php_exam/albums
        $uriPath = parse_url($uri, PHP_URL_PATH);
        
        // get base path of where index.php is running
        // eg /PHP_SOFT/php_exam
        // replace and escape backslahes (windows)
        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

        // check if the uriPath starts with the basePath, remove if true
        if (strpos($uriPath, $basePath) === 0) {
            $uriPath = substr($uriPath, strlen($basePath));
        }

        // make sure it starts with a forward slash
        $uriPath = '/' . ltrim($uriPath, '/');

        // remove trailing slash
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