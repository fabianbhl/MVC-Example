<?php

/**
 * The AuthMiddleware class checks for an Authorization header in HTTP requests. 
 * If the header is missing, it sends a 401 Unauthorized response and stops the request. 
 * If present, it allows the request to proceed to the next middleware or handler 
 * by executing the $next closure.
 * 
 * This is just an example of a middleware and just let's any request through if the 
 * Authorization header is present without any further validation. In a real-world
 * application, you would typically validate the token or credentials in the Authorization header.
 */

namespace App\Middleware;

use App\Interfaces\Middleware;
use Closure;

class AuthMiddleware implements Middleware {
    public function handle($request, $next) {
        if (!isset($request['headers']['Authorization'])) { // Check if the Authorization header is present
            http_response_code(401);
            exit('Unauthorized');
        }
        return $next($request); // Proceed with the next middleware or handler
    }
}
