<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;

class ErrorHandler
{
    public static function register($app)
    {
        $errorMiddleware = $app->addErrorMiddleware(true, true, true);

        // Handle Method Not Allowed Exception
        $errorMiddleware->setErrorHandler(
            HttpMethodNotAllowedException::class,
            function (Request $request, \Throwable $exception, bool $displayErrorDetails) {
                $response = new \Nyholm\Psr7\Response();
                $response->getBody()->write(json_encode([
                    'error' => true,
                    'message' => 'This endpoint only accepts specific HTTP methods. Please refer to the API documentation.',
                    'documentation_url' => 'refer readme.md for documentation'
                ]));
                return $response->withStatus(405)->withHeader('Content-Type', 'application/json');
            }
        );

        // Handle Not Found Exception
        $errorMiddleware->setErrorHandler(
            HttpNotFoundException::class,
            function (Request $request, \Throwable $exception, bool $displayErrorDetails) {
                $response = new \Nyholm\Psr7\Response();
                $response->getBody()->write(json_encode([
                    'error' => true,
                    'message' => 'The requested endpoint was not found. Please check the URL or refer to the API documentation.',
                    'documentation_url' => 'refer readme.md for documentation'
                ]));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }
        );

        return $errorMiddleware;
    }
}