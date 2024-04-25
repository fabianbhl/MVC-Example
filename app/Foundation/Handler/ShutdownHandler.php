<?php

/**
 * The ShutdownHandler includes a method that acts as a shutdown function to catch fatal errors that are not handled 
 * by the normal error handling process, such as E_ERROR, E_PARSE, E_CORE_ERROR, and E_COMPILE_ERROR. 
 * When such an error occurs, the method constructs an \ErrorException with the error details and 
 * passes it to the ExceptionHandler for logging and returning a structured JSON response to the client. 
 */

namespace App\Foundation\Handler;

/**
 * Class ShutdownHandler
 * @package App\Foundation\Handler
 */
class ShutdownHandler
{
    /**
     * Handle fatal errors
     */
    public static function handleShutdown()
    {
        $last_error = error_get_last();
        // If the last error is a fatal error, handle it as an exception
        if ($last_error && in_array($last_error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            ExceptionHandler::handleException(new \ErrorException($last_error['message'], 0, $last_error['type'], $last_error['file'], $last_error['line']));
        }
    }
}
