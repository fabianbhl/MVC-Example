<?php

/**
 * This bootstrap file initializes the application by setting up Composer's autoloader, 
 * loading environment variables with Dotenv, and configuring error reporting based on 
 * the environment. It also registers custom handlers for managing errors, exceptions, 
 * and the application shutdown, ensuring consistent error handling across the application.
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Foundation\Handler\ErrorHandler;
use App\Foundation\Handler\ExceptionHandler;
use App\Foundation\Handler\ShutdownHandler;

// Load environment variables
$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

// Environment-based error reporting
error_reporting($_ENV['APP_ENV'] === 'development' ? E_ALL : 0);
ini_set('display_errors', $_ENV['APP_ENV'] === 'development' ? '1' : '0');
ini_set('display_startup_errors', $_ENV['APP_ENV'] === 'development' ? '1' : '0');

// Register handlers
set_error_handler([ErrorHandler::class, 'handleError']);
set_exception_handler([ExceptionHandler::class, 'handleException']);
register_shutdown_function([ShutdownHandler::class, 'handleShutdown']);
