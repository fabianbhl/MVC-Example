<?php

/**
 * The ErrorHandler class includes a method to convert PHP errors such as warnings or notices into \ErrorException objects, 
 * but only if they fall within the current error reporting threshold, thus allowing the application to handle these 
 * errors more robustly. For example, it would catch and convert errors like E_WARNING or E_NOTICE into exceptions 
 * if they are not explicitly suppressed.
 */

namespace App\Foundation\Handler;

/**
 * Class ErrorHandler
 * @package App\Foundation\Handler
 */
class ErrorHandler
{
    /**
     * Convert PHP errors to ErrorException objects
     *
     * @param int $severity
     * @param string $message
     * @param string $file
     * @param int $line
     * @throws \ErrorException
     */
    public static function handleError($severity, $message, $file, $line)
    {
        if (!(error_reporting() & $severity)) {
            return;
        }
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }
}
