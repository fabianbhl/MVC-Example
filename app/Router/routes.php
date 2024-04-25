<?php

/**
 * This script registers routes using the Router object. Each router->register() 
 * defines a route with an HTTP method, URL path, and a controller method as the callback. 
 * For example, MainController@index handles the home page. Some routes, like 'auth', 
 * include middleware for pre-processing, such as authentication checks using AuthMiddleware. 
 * The routes cover different application sections, with parameters ({name:string}) 
 * in URLs where needed, directing requests to appropriate controllers.
 */

use App\Controller\AboutController;
use App\Controller\ErrorController;

use App\Middleware\AuthMiddleware;

$router->register('GET', '', 'MainController@index');
$router->register('GET', 'auth', 'MainController@auth', [new AuthMiddleware()]);
$router->register('GET', 'about', 'AboutController@index');
$router->register('GET', 'about/{name:string}', 'AboutController@name');

// ErrorController does not exist yet, so this route will throw a 500 error to test error handling
$router->register('GET', '500_test', 'ErrorController@test');