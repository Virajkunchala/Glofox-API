<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\BookingController;
use App\Services\BookingService;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ResponseInterface;

class BookingControllerTest extends TestCase
{
    private $controller;
    private $mockBookingService;
    private $responseFactory;
    private $serverRequestCreator;

    protected function setUp(): void
    {
        $this->responseFactory = new Psr17Factory();
        $this->serverRequestCreator = new ServerRequestCreator(
            $this->responseFactory,
            $this->responseFactory,
            $this->responseFactory,
            $this->responseFactory
        );


        // inject the mock BookingService into the BookingController
        $this->controller = new BookingController();
        $this->mockBookingService = $this->createMock(BookingService::class);
        $this->controller->setBookingService($this->mockBookingService);
    }

    public function testCreateBookingWithValidData()
    {
        $data = [
            'name' => 'Viraj',
            'date' => '2025-01-05'
        ];

        // createBooking method of BookingService to return a booking
        $this->mockBookingService->expects($this->once())
            ->method('createBooking')
            ->with($this->equalTo($data))
            ->willReturn($data);

        // a POST request with the data
        $request = $this->serverRequestCreator->fromGlobals()
            ->withMethod('POST')
            ->withParsedBody($data);
        $response = $this->responseFactory->createResponse();

        $result = $this->controller->createBooking($request, $response, []);

        // Assert the response status 
        $this->assertEquals(201, $result->getStatusCode());
        $this->assertEquals('application/json', $result->getHeaderLine('Content-Type'));
        
        $responseBody = json_decode((string)$result->getBody(), true);
        $this->assertEquals('Booking created successfully', $responseBody['message']);
        $this->assertEquals($data['name'], $responseBody['booking']['name']);
        $this->assertEquals($data['date'], $responseBody['booking']['date']);
    }

    public function testCreateBookingWithInvalidData()
    {
        $data = [
            'name' => '',  // Missing name
            'date' => 'invalid-date'  // Invalid date format
        ];

        //POST request with the invalid data
        $request = $this->serverRequestCreator->fromGlobals()
            ->withMethod('POST')
            ->withParsedBody($data);
        $response = $this->responseFactory->createResponse();

        // validation to return errors
        $this->mockBookingService->expects($this->once())
            ->method('validateBookingDate')
            ->with($this->equalTo($data))
            ->willReturn(['Missing required field: name', 'Invalid date format. Use YYYY-MM-DD.']);

        $result = $this->controller->createBooking($request, $response, []);

        // Assert the response status and error message
        $this->assertEquals(400, $result->getStatusCode());
        $this->assertEquals('application/json', $result->getHeaderLine('Content-Type'));
        
        $responseBody = json_decode((string)$result->getBody(), true);
        $this->assertArrayHasKey('error', $responseBody);
        $this->assertContains('Missing required field: name', $responseBody['error']);
        $this->assertContains('Invalid date format. Use YYYY-MM-DD.', $responseBody['error']);
    }

    public function testCreateBookingForNonexistentClassDate()
    {
        $data = [
            'name' => 'Viraj',
            'date' => '2025-01-10'  // when without class
        ];

        // Simulate a POST request with the invalid data
        $request = $this->serverRequestCreator->fromGlobals()
            ->withMethod('POST')
            ->withParsedBody($data);
        $response = $this->responseFactory->createResponse();

        // Mock validation to return errors
        $this->mockBookingService->expects($this->once())
            ->method('validateBookingDate')
            ->with($this->equalTo($data))
            ->willReturn(['No class exists for the date']);

        $result = $this->controller->createBooking($request, $response, []);

        // Assert the response status and error message
        $this->assertEquals(400, $result->getStatusCode());
        $this->assertEquals('application/json', $result->getHeaderLine('Content-Type'));
        
        $responseBody = json_decode((string)$result->getBody(), true);
        $this->assertArrayHasKey('error', $responseBody);
        $this->assertContains('No class exists for the date', $responseBody['error']);
    }
}
