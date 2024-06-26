<?php

/**
 * In MVC architecture, the controller handles user interactions, 
 * managing data flow between the model and the view (or json output). 
 * It processes user requests—like form submissions or button 
 * clicks—by fetching and preparing data from the model, 
 * and then sending it to the view for user presentation.
 * 
 * In the example code, the AboutController class deals with the "About" section. 
 * It features two methods: index() which provides a JSON response with 
 * a pre-set name, and name($request) which returns a JSON response 
 * containing a name extracted from URL parameters. Controllers are vital 
 * for keeping application concerns separate, enhancing maintainability 
 * and testability of the codebase.
 */

namespace App\Controller;

use App\Foundation\Response\JsonResponse;

/**
 * Class AboutController
 * @package App\Controller
 */
class AboutController {
    /**
     * Index method
     */
    public function index(): void {
        $data = ["name" => "Fabian"];
        JsonResponse::data($data);
    }

    /**
     * Name method
     * 
     * @param array $request
     */
    public function name(array $request): void{
        $data = ["name" => $request['params']['name']]; // $request['params'] is an associative array with the URL parameters
        JsonResponse::data($data);
    }
}
