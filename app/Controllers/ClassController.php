<?php
namespace App\Controllers;

use App\Services\ClassService;

class ClassController
{
    private $classService;


    public function __construct()
    {
        
        $this->classService = new ClassService();
    }


    public function index($request, $response, $args)
    {
        $response->getBody()->write("Ping Pong");
        return $response;
    }

    public function createClass($request, $response, $args)
    {
      
        //Get the data from the request
        $data=$request->getParsedBody();

        $validation_errors = $this->classService->validateClassData($data);

        if (!empty($validation_errors)) {
            $response->getBody()->write(json_encode(['error' => $validation_errors]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $classes=$this->classService->createClass($data);

        // Respond with success (201 Created) and a JSON message
        $response->getBody()->write(json_encode([
            'message' => 'Classes created successfully',
            'classes'=>$classes
        ]));

        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }
}