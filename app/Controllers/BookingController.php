<?php
namespace App\Controllers;

use App\Services\BookingService;
use App\Utils\FileStorage;

class BookingController
{
    private $bookingService;

    public function __construct()
    {
        $bookingStorage = new FileStorage(__DIR__ . '/../Storage/booking_data.json');
        $classStorage = new FileStorage(__DIR__ . '/../Storage/class_data.json');
        $this->bookingService = new BookingService($bookingStorage, $classStorage);
    }


    public function createBooking($request,$response,$args)
    {
        //Get the data from the request
        $data=$request->getParsedBody();

        $validation_errors = $this->bookingService->validateBookingDate($data);

        if (!empty($validation_errors)) {
            $response->getBody()->write(json_encode(['error' => $validation_errors]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $booking=$this->bookingService->createBooking($data);

        //Return the response
        $response->getBody()->write(json_encode([
            'message' => 'Booking created successfully',
            'booking'=>$booking
        ]));

        return $response
            ->withStatus(201)
            ->withHeader('Content-Type', 'application/json');

    }

     // Add setter for testing
     public function setBookingService(BookingService $service)
     {
         $this->bookingService = $service;
     }

}