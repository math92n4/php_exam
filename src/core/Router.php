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

        // Iterate through all routes for the current method
        foreach ($this->routes[$method] as $route => $action) {
            // Convert dynamic parts like {id} to regex
            $routeRegex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route);
            
            // Match the route against the current path
            if (preg_match('#^' . $routeRegex . '$#', $path, $matches)) {
                $callback = $action;
                // Extract named parameters from the route and pass them as an associative array
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                break;
            }
        }

        if (!$callback) {
            http_response_code(404);
            echo json_encode(["error" => "Not found"]);
            return;
        }

        $searchQuery = $this->request->getQueryParam('s'); // Capture the search text from the query string
        if ($searchQuery) {
            $params['searchTerm'] = $searchQuery; // Pass it as a 'search' parameter to the controller
        }

        // Split the callback into controller and method
        [$controller, $action] = explode('@', $callback);

        // Require the controller file
        require_once "src/controller/{$controller}.php";
        $controllerInstance = new $controller($this->request);

        // Call the controller method and pass individual parameters from the $params array
        echo json_encode(call_user_func_array([$controllerInstance, $action], $params)); // Pass parameters dynamically
    }
}
