<?php

/**
 * JsonResponse provides methods to send structured JSON responses from the application. 
 * The send() method sends a JSON response with customizable data and HTTP status code, 
 * while the error() method sends a standardized error response with an error message, 
 * error code, and HTTP status code. Additionally, the data() method sends a success 
 * response with the provided payload data. 
 * Although the data method is redundant, it can be useful for consistency in response formatting,
 * keeping in mind, that this class might be extended in the future to include additional functionality.
 */

namespace App\Foundation\Response;

/**
 * Class JsonResponse
 * @package App\Foundation\Response
 */
class JsonResponse
{
    /**
     * Send a JSON response with a given data array and HTTP status code.
     *
     * @param array $data The data to encode as JSON.
     * @param int $statusCode The HTTP status code.
     */
    public static function send($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;  // Ensure no further output is sent
    }

    /**
     * Send a standardized error response.
     *
     * @param string $message The error message.
     * @param int $code The error code (default 500).
     * @param int $statusCode The HTTP status code (default 500).
     */
    public static function error($message, $code = 500, $statusCode = 500)
    {
        $data = [
            "error" => [
                "code" => $code,
                "message" => $message
            ]
        ];
        self::send($data, $statusCode);
    }

    /**
     * Send a success response with data.
     *
     * @param array $data The payload to send.
     * @param int $statusCode The HTTP status code (default 200).
     */
    public static function data($data, $statusCode = 200)
    {
        self::send(["data" => $data], $statusCode);
    }
}
