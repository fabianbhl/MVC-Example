<?php

/**
 * The RateLimitMiddleware class is responsible for enforcing a rate limit on incoming HTTP requests, 
 * allowing only a certain number of requests per minute. This middleware is used to prevent abuse 
 * of the server resources and ensure fair usage by clients.
 * 
 * Middleware classes in web applications are used to intercept and process HTTP requests, 
 * performing actions such as authentication, logging, rate limiting, and more. They provide a 
 * modular and reusable way to add functionality to the request-response cycle, enhancing security, 
 * performance, and maintainability of the application.
 * 
 * In this case, I implemented a simple rate limiting mechanism that tracks the number of requests
 * and stores them in a session. If the request limit is exceeded within a minute, the middleware
 * returns a 429 Too Many Requests response, indicating that the client should wait and try again later.
 * Instead of just checking for requests in a minute, I use a sliding window approach to reset the
 * request count and time if a minute has passed since the rate limiting period started.
 * 
 * KEEP IN MIND: This is a basic implementation for demonstration purposes. In a production environment,
 * you would likely use a more sophisticated rate limiting solution, such as a token bucket algorithm
 * using redis or a dedicated rate limiting service. Maybe I will consider implementing a more advanced
 * rate limiting mechanism in the future. 
 */

namespace App\Middleware;

use App\Interface\Middleware;
use App\Foundation\Response\JsonResponse;
use Closure;

class RateLimitMiddleware implements Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param array $request The HTTP request data.
     * @param  Closure  $next The next middleware or handler to pass control to.
     * @param int $limit The request limit per minute.
     * @return mixed
     */
    public function handle(array $request, Closure $next, int $limit = 10): mixed {
        // Start the session if not already started
        session_start();
        $now = time();

        // Check if the timestamps array is set in the session
        if (!isset($_SESSION['timestamps'])) {
            $_SESSION['timestamps'] = [];
        }

        // Filter out timestamps older than 60 seconds
        $_SESSION['timestamps'] = array_filter($_SESSION['timestamps'], function($timestamp) use ($now) {
            return ($now - $timestamp) < 60;
        });

        // Check if the request limit has been exceeded
        if (count($_SESSION['timestamps']) >= $limit) {
            http_response_code(429);
            JsonResponse::error("Rate limit exceeded. Please wait and try again.", 429, 429);
        }

        // Add the current timestamp to the session
        $_SESSION['timestamps'][] = $now;
        return $next($request);
    }
}