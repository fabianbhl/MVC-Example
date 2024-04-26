<?php

return [
    'global' => [
        // List of middleware to be applied globally to all routes
        \App\Middleware\RateLimitMiddleware::class => 10, // Allow up to 10 requests per minute
    ]
];