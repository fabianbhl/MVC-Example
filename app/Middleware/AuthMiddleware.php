<?php

/**
 * The AuthMiddleware class checks for an Authorization header in HTTP requests. 
 * If the header is missing, it sends a 401 Unauthorized response and stops the request. 
 * If present, it allows the request to proceed to the next middleware or handler 
 * by executing the $next closure.
 * 
 * This is just an example of a middleware and just lets any request through if the
 * Authorization header is present without any further validation. In a real-world
 * application, you would typically validate the token or credentials in the Authorization header.
 */

namespace App\Middleware;

use App\Interface\Middleware;
use App\Foundation\Response\JsonResponse;
use Closure;

/**
 * Class AuthMiddleware
 * @package App\Middleware
 */
class AuthMiddleware implements Middleware {
    /**
     * Handle method for the AuthMiddleware class.
     * 
     * @param array $request The HTTP request data.
     * @param Closure $next The next middleware or handler to pass control to.
     */
    public function handle(array $request, Closure $next): mixed
    {
        if (!isset($request['headers']['Authorization'])) { // Check if the Authorization header is present
            http_response_code(401);
            JsonResponse::error("Unauthorized", 401);
            exit;
        }
        return $next($request); // Proceed with the next middleware or handler
    }
}
