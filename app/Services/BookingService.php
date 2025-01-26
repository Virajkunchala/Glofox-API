<?php
namespace App\Services;

use App\Utils\FileStorage;

class BookingService
{

    private $bookingStorage;
    private $classStorage;

    public function __construct(FileStorage $bookingStorage, FileStorage $classStorage)
    {


        $this->bookingStorage = $bookingStorage;
        $this->classStorage = $classStorage;
        
    }

    public function validateBookingDate(array $date):array
    {
        $errors = [];

        //fileds validation
        $required_fields=['name','date'];

        foreach($required_fields as $field){
            if (empty($date[$field])) {
                $errors[] = "Missing required field: $field";
            }
        }

        //check the format of the date 
        if(!empty($date['date']) && !strtotime($date['date'])){
            $errors[] = "Invalid date format. Use YYYY-MM-DD.";
        }

        //check if there is class exisit for the date

        $classes = $this->classStorage->read();
        $classExists=array_filter($classes,function($class) use ($date){
            return $class['date'] == $date['date'];
        });

        if(empty($classExists)){
            $errors[] = "No class exists for the date";
        }

        return $errors;

    }

    public function createBooking(array $data): array
    {
        $booking = [
            'name' => $data['name'],
            'date' => $data['date']
        ];

        // Save booking to storage
        $this->bookingStorage->append([$booking]);

        return $booking;
    }

}