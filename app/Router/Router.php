<?php

/**
 * The Router class manages the routing of HTTP requests. It maintains a list of routes, 
 * each defined with an HTTP method, URL pattern, callback, and optionally, middlewares. 
 * Here's a brief overview of its functionality:
 * 
 *      Route Registration: Routes are added with the register method specifying 
 *      the method, URL pattern, callback, and middlewares.
 * 
 *      Dispatching Requests: The dispatch method handles incoming requests by matching 
 *      them to registered routes. If a match is found, it creates a request object, 
 *      resolves the callback into a callable, applies any middlewares, and executes the handler.
 * 
 *      URL Matching and Middleware Application: It uses regular expressions to match URLs 
 *      and extract parameters, and applies middlewares in sequence for request processing or modification.
 * 
 * If no route matches, it returns a 404 Not Found response. This structure allows for efficient 
 * and organized handling of web requests, leveraging middlewares for additional request processing.
 */

namespace App\Router;

/**
 * Class Router
 * @package App\Router
 */
class Router {
    private $routes = [];

    /**
     * Register a new route with the router
     * 
     * @param string $method The HTTP method (GET, POST, PUT, DELETE)
     * @param string $pattern The URL pattern with optional parameters in {param} format
     * @param mixed $callback The callback function or controller method to execute
     * @param array $middlewares An array of middleware classes to apply
     */
    public function register($method, $pattern, $callback, $middlewares = []) {
        $this->routes[] = ['method' => $method, 'pattern' => $pattern, 'callback' => $callback, 'middlewares' => $middlewares];
    }

    /**
     * Dispatch the request to the appropriate route
     */
    public function dispatch() {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); // Extract the URI and method from the request
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) { // Iterate over the registered routes to find a match
            if ($method === $route['method'] && $this->match($route['pattern'], $uri, $params)) {
                $request = [
                    'uri' => $uri,
                    'method' => $method,
                    'params' => $params, // Parameters extracted from URL
                    'headers' => getallheaders(), // Capture headers
                    'body' => file_get_contents('php://input') // Get the body of the request
                ];

                $handler = $this->resolveCallback($route['callback']);
                $handler = $this->applyMiddlewares($route['middlewares'], $handler);

                return $handler($request);
            }
        }

        http_response_code(404);
        $data = ["error" => ["code" => 404, "message" => "Not Found"]];
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Resolve the callback into a callable function
     * 
     * @param mixed $callback The callback function or controller method
     * @return callable The resolved callable function
     */
    private function resolveCallback($callback) {
        if (is_string($callback) && strpos($callback, '@') !== false) { // Check if the callback is in [object, method] format like MainController@index
            list($class, $method) = explode('@', $callback, 2); // Split the class and method names by @
            $class = "App\\Controller\\" . $class;
            return [new $class, $method];
        }
        return $callback;
    }

    /**
     * Match the URL pattern against the URI and extract parameters
     * 
     * @param string $pattern The URL pattern with optional parameters in {param} format
     * @param string $uri The request URI
     * @param array $params An array to store the extracted parameters
     * @return bool True if the pattern matches the URI, false otherwise
     */
    private function match($pattern, $uri, &$params) {
        $params = [];
        $pattern = preg_replace_callback('/\{(\w+)(:\w+)?\}/', function ($matches) { // Replace the {param} placeholders with named regex capture groups
            $paramName = $matches[1]; // Extract the parameter name and type if provided
            $type = isset($matches[2]) ? trim($matches[2], ':') : 'string';

            switch ($type) { // Return the named capture group based on the type
                case 'int': return '(?P<' . $paramName . '>\d+)';
                case 'string': 
                default: 
                    return '(?P<' . $paramName . '>[^/]+)';
            }
        }, $pattern);
        $pattern = "@^$pattern$@";
    
        // Match the pattern against the URI
        if (preg_match($pattern, $uri, $matches)) {
            // Extract the named parameters into the $params array
            foreach ($matches as $key => $match) {
                // Skip the first element which is the full match
                if (is_string($key)) {
                    $params[$key] = $match;
                }
            }
            return true;
        }
        return false;
    }    

    /**
     * Apply middlewares to the handler in reverse order
     * 
     * @param array $middlewares An array of middleware classes
     * @param callable $handler The handler function to apply middlewares to
     * @return callable The final handler with middlewares applied
     */
    private function applyMiddlewares($middlewares, $handler) {
        if (is_array($handler)) { // Wrap the handler in a closure if it's a function
            $handler = function($request) use ($handler) {
                return call_user_func($handler, $request);
            };
        }
    
        while ($middleware = array_pop($middlewares)) { // Apply middleware in reverse order
            $handler = function($request) use ($middleware, $handler) {
                return $middleware->handle($request, $handler);
            };
        }
        return $handler;
    }
}