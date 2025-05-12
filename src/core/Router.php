<?php
class Router {

    private $request;

    private $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];

    public function __construct($request) {
        $this->request = $request;
    }

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function put($path, $callback) {
        $this->routes['PUT'][$path] = $callback;
    }

    public function delete($path, $callback) {
        $this->routes['DELETE'][$path] = $callback;
    }


    public function resolve() {
        $method = $this->request->method();
        $path = $this->request->getPath();
        $callback = $this->routes[$method][$path] ?? null;

        if (!$callback) {
            http_response_code(404);
            echo json_encode(["error" => "Not found"]);
            return;
        }

        [$controller, $action] = explode('@', $callback);
        require_once "src/controller/{$controller}.php";
        $controllerInstance = new $controller();
        echo json_encode($controllerInstance->$action($this->request));
    }
}