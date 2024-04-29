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

use App\Foundation\Response\JsonResponse;

/**
 * Class Router
 * @package App\Router
 */
class Router {
    private array $routes = [];
    private array $globalMiddleware = [];

    /**
     * Load global middleware on Router instantiation
     */
    public function __construct() {
        $this->loadGlobalMiddleware();
    }

    /**
     * Load global middleware from the middleware configuration file
     */
    private function loadGlobalMiddleware(): void {
        // Retrieve the array of middleware class names from the middleware.php
        $middlewareConfig = require dirname(__DIR__) . '/core/middleware.php';
        
        // Iterate over the global middleware configuration and instantiate each middleware
        foreach ($middlewareConfig['global'] as $middlewareClass => $parameters) {
            // Check if parameters are provided as an array; otherwise, wrap them in one
            if (!is_array($parameters)) {
                $parameters = [$parameters]; // Ensure parameters are always treated as an array
            }

            // Instantiate each middleware with the provided parameters
            $this->globalMiddleware[] = new $middlewareClass(...$parameters);
        }
    }

    /**
     * Register a new route with the router
     * 
     * @param string $method The HTTP method (GET, POST, PUT, DELETE)
     * @param string $pattern The URL pattern with optional parameters in {param} format
     * @param mixed $callback The callback function or controller method to execute
     * @param array $middlewares An array of middleware classes to apply
     */
    public function register(string $method, string $pattern, callable $callback, array $middlewares = []): void {
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
                $middleware = array_merge($this->globalMiddleware, $route['middlewares']); // Combine global and specific middlewares
                $handler = $this->applyMiddleware($middleware, $handler);

                return $handler($request);
            }
        }

        JsonResponse::error("Not Found", 404, 404);
        return null;
    }

    /**
     * Resolve the callback into a callable function
     * 
     * @param mixed $callback The callback function or controller method
     * @return callable The resolved callable function
     */
    private function resolveCallback(callable $callback): callable {
        if (is_string($callback) && str_contains($callback, '@') !== false) { // Check if the callback is in [object, method] format like MainController@index
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
    private function match(string $pattern, string $uri, array &$params): bool{
        $params = [];
        $pattern = preg_replace_callback('/\{(\w+)(:\w+)?}/', function ($matches) { // Replace the {param} placeholders with named regex capture groups
            $paramName = $matches[1]; // Extract the parameter name and type if provided
            $type = isset($matches[2]) ? trim($matches[2], ':') : 'string';

            return match ($type) {
                'int' => '(?P<' . $paramName . '>\d+)',
                default => '(?P<' . $paramName . '>[^/]+)',
            };
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
     * @param array $middleware An array of middleware classes
     * @param callable $handler The handler function to apply middlewares to
     * @return callable The final handler with middlewares applied
     */
    private function applyMiddleware(array $middleware, callable $handler): callable {
        if (is_array($handler)) { // Wrap the handler in a closure if it's a function
            $handler = function($request) use ($handler) {
                return call_user_func($handler, $request);
            };
        }
    
        while ($_middleware = array_pop($middleware)) { // Apply middleware in reverse order
            $handler = function($request) use ($_middleware, $handler) {
                return $_middleware->handle($request, $handler);
            };
        }
        return $handler;
    }
}