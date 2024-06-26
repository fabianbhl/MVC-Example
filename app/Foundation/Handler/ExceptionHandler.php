<?php

/**
 * The ExceptionHandler includes a method to handle uncaught exceptions throughout the application. 
 * It logs the exception details, sets the HTTP response code to 500, and uses the JsonResponse class 
 * to send a JSON response with the exception message if error displaying is enabled, or a generic "Internal Server Error. 
 * Try again later." message if it is not. This approach ensures that the system communicates errors clearly to the client, 
 * while keeping sensitive details secure in production environments.
 */

namespace App\Foundation\Handler;

use App\Foundation\Response\JsonResponse;
use Error;

/**
 * Class ExceptionHandler
 * @package App\Foundation\Handler
 */
class ExceptionHandler
{
    /**
     * Handle uncaught exceptions
     *
     * @param Error $exception
     */
    public static function handleException(Error $exception): void {
        error_log($exception->getMessage());  // Log the exception details
        JsonResponse::error(ini_get('display_errors') ? $exception->getMessage() : "Internal Server Error. Try again later.");
    }
}
