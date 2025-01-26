<?php

namespace App\Utils;

class FileStorage
{
    private  $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    public function read():array
    {
        $data = file_get_contents($this->filePath);
        return json_decode($data, true) ?? [];
    }

    public function write(array $data):void
    {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function append(array $newdata):void
    {
        $currentData = $this->read();
        $mergedData = array_merge($currentData, $newdata);
        $this->write($mergedData);
    }

    // public static function load()
    // {
    //     if (file_exists(self::$filePath)) {
    //         $data = file_get_contents(self::$filePath);
    //         return json_decode($data, true);
    //     }
    //     return [];
    // }

    // public static function save($data)
    // {
    //     // Convert the classes array to a JSON string
    //     $data = json_encode($data, JSON_PRETTY_PRINT);

    //     // Save the JSON data to the file
    //     file_put_contents(self::$filePath, $data);
    // }
}

