<?php
namespace App\Services;

use App\Utils\FileStorage;

class ClassService
{

    private $storage;

    public function __construct()
    {

        $this->storage = new FileStorage(__DIR__ . '/../Storage/class_data.json');
        
    }

    public function validateClassData(array $data):array
    {
        $errors = [];

        //fileds validation
        $required_fields=['name','start_date','end_date','capacity'];

        foreach($required_fields as $field){
            if (empty($data[$field])) {
                $errors[] = "Missing required field: $field";
            }
        }

        //check the order of the dates 

        if(!empty($data['start_date']) && !empty($data['end_date'])){
            if(strtotime($data['start_date']) > strtotime($data['end_date'])){
                $errors[] = "End date should be greater than start date";
            }
        }

        //check the capacity positive number
        if (!isset($data['capacity']) || !is_numeric($data['capacity']) || $data['capacity'] <= 0) {
            $errors[] = "Capacity should be a positive number";
        }        

        return $errors;

    }

    public function createClass(array $data): array
    {
        $start = new \DateTime($data['start_date']);
        $end = (new \DateTime($data['end_date']))->modify('+1 day');
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($start, $interval, $end);

        $classes = [];
        foreach ($period as $date) {
            $classes[] = [
                'name' => $data['name'],
                'date' => $date->format('Y-m-d'),
                'capacity' => $data['capacity']
            ];
        }

        // Save classes to storage
        $this->storage->append($classes);

        return $classes;
    }



    
}