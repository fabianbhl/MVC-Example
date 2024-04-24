<?php

/**
 * In MVC architecture, the controller handles user interactions, 
 * managing data flow between the model and the view (or json output). 
 * It processes user requests—like form submissions or button 
 * clicks—by fetching and preparing data from the model, 
 * and then sending it to the view for user presentation.
 * 
 * The MainController class in the provided code handles the main entry points 
 * of the application. It includes two methods: index() and auth(). 
 * Both methods generate a JSON response containing a static message. 
 * The index() method is a general endpoint, while the auth() method uses 
 * authentication middleware as stated in routes.php.
 */

namespace App\Controller;

class MainController {
    public function index() {
        $data = ["message" => "Lorem ipsum dolor sit amet"];
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function auth() {
        $data = ["message" => "Lorem ipsum dolor sit amet"];
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
