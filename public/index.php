<?php

/**
 * The index.php is the main entry point for the web application, managing HTTP requests:
 * 
 *      Composer Autoloader: Initializes Composer's autoloader to handle dependencies 
 *      and class loading automatically.
 * 
 *      Router Setup: Creates a Router instance and loads route definitions that 
 *      map URLs to controller actions.
 * 
 *      Request Dispatching: Executes router->dispatch() to match the incoming 
 *      HTTP request to a route and initiate the corresponding controller method, 
 *         or return a 404 Not Found response if no match is found.
 */

require '../vendor/autoload.php'; 

use App\Router\Router;

$router = new Router(); 
require __DIR__ . '/../app/Router/routes.php';

$router->dispatch();