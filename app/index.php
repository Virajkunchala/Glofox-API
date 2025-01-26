<?php

require_once __DIR__ . '/../vendor/autoload.php';


use Slim\Factory\AppFactory;
use Slim\Middleware\BodyParsingMiddleware;
use App\Middleware\ErrorHandler;



$app = AppFactory::create();
$app->add(new BodyParsingMiddleware()); 


// Register Error Handlers
ErrorHandler::register($app);


require_once __DIR__ . '/Routes/api.php';


// Define  routes
// $app->get('/', function ($request, $response, $args) {
//     $response->getBody()->write("Ping Pong");
//     return $response;
// });

// // Define  routes
// $app->get('/classes', function ($request, $response, $args) {
//     $response->getBody()->write("Ping Pong");
//     return $response;
// });

// Run the app
$app->run();
