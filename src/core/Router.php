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
        $callback = null;
        $params = [];

        // loop through all routes for $method
        foreach ($this->routes[$method] as $route => $action) {
            // handle path params with regex eg {id}
            $routeRegex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route);

            // check if the current request path matches the route regex
            if (preg_match('#^' . $routeRegex . '$#', $path, $matches)) {
                $callback = $action;
                // pass the params to the callback function
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                break;
            }
        }

        if (!$callback) {
            http_response_code(404);
            header('Content-Type: application/json');
            Logger::log("$method $path 404 ");
            echo json_encode(["error" => "Not found"]);
            return;
        }

        // split the controller and action
        [$controller, $action] = explode('@', $callback);

        // create controller instance
        $controllerInstance = new $controller($this->request);

        // call the controller with the correct action and with params
        $result = call_user_func_array([$controllerInstance, $action], $params);

        $statusCode = http_response_code();
        Logger::log("$method $path $statusCode " . ($statusCode === 200 ? 'OK' : ''));

        echo json_encode($result);

    } 

        
}

