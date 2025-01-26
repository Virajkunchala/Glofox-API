<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\ClassController;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

class ClassControllerTest extends TestCase
{
    private $controller;
    private $responseFactory;
    private $serverRequestCreator;

    protected function setUp(): void
    {
        // Instantiate the ClassController
        $this->controller = new ClassController();

        //  dependecy's
        $this->responseFactory = new Psr17Factory();
        $this->serverRequestCreator = new ServerRequestCreator(
            $this->responseFactory, 
            $this->responseFactory, 
            $this->responseFactory, 
            $this->responseFactory 
        );
    }

    public function testIndex()
    {
        $request = $this->serverRequestCreator->fromGlobals();
        $response = $this->responseFactory->createResponse();

        $result = $this->controller->index($request, $response, []);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals('Ping Pong', (string)$result->getBody());
    }

    public function testCreateClassWithValidData()
    {
        $data = [
            'name' => 'pilates test',
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-05',
            'capacity' => 20
        ];

        $request = $this->serverRequestCreator->fromGlobals()
            ->withMethod('POST')
            ->withParsedBody($data);
        $response = $this->responseFactory->createResponse();

        $result = $this->controller->createClass($request, $response, []);

        $this->assertEquals(201, $result->getStatusCode());
        $this->assertEquals('application/json', $result->getHeaderLine('Content-Type'));

        $responseBody = json_decode((string)$result->getBody(), true);
        $this->assertEquals('Classes created successfully', $responseBody['message']);
        $this->assertCount(5, $responseBody['classes']); // 5 days from start_date to end_date
    }

    public function testCreateClassWithInvalidData()
    {
        $data = [
            'name' => 'pilates test',
            'start_date' => '2025-01-05',
            'end_date' => '2025-01-01', // Invalid: end_date is before start_date
            'capacity' => -10 // Invalid: negative capacity
        ];

        $request = $this->serverRequestCreator->fromGlobals()
            ->withMethod('POST')
            ->withParsedBody($data);
        $response = $this->responseFactory->createResponse();

        $result = $this->controller->createClass($request, $response, []);

        $this->assertEquals(400, $result->getStatusCode());
        $this->assertEquals('application/json', $result->getHeaderLine('Content-Type'));

        $responseBody = json_decode((string)$result->getBody(), true);
        $this->assertArrayHasKey('error', $responseBody);
        $this->assertContains('End date should be greater than start date', $responseBody['error']);
        $this->assertContains('Capacity should be a positive number', $responseBody['error']);
    }
}
