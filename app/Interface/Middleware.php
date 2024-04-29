<?php

/**
 * The Middleware interface specifies a handle method for classes implementing it, 
 * ensuring they process HTTP requests and potentially pass control to the next middleware in 
 * the sequence using a Closure called $next. This interface promotes modularity and reusability 
 * by standardizing how middleware should behave in web applications.
 * 
 * Interfaces are vital as they define clear contracts for classes, facilitating predictable behavior 
 * across implementations and ensuring compatibility in larger, complex systems. They help enforce structure, 
 * making the code more robust, maintainable, and easier to extend.
 */

namespace App\Interface;

use Closure;

/**
 * Interface Middleware
 * @package App\Interface
 */
interface Middleware {
    public function handle(array $request, Closure $next);
}
