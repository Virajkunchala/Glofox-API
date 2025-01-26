<?php

use PHPUnit\Framework\TestCase;
use App\Services\ClassService;

class ClassServiceTest extends TestCase
{
    private $classService;

    protected function setUp(): void
    {
        $this->classService = new ClassService();
    }

    public function testValidateClassDataValid()
    {
        $data = [
            'name' => 'pilates test class',
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-05',
            'capacity' => 10
        ];

        $result = $this->classService->validateClassData($data);

        $this->assertEmpty($result);
    }

    public function testValidateClassDataInvalid()
    {
        $data = [
            'name' => 'pilates test class',
            'start_date' => '2025-01-05',
            'end_date' => '2025-01-01', // Invalid: end_date is before start_date
            'capacity' => 0 // Invalid: capacity is not a positive number
        ];

        $result = $this->classService->validateClassData($data);

        $this->assertNotEmpty($result);
        $this->assertContains('End date should be greater than start date', $result);
        $this->assertContains('Capacity should be a positive number', $result);
    }

    public function testCreateClass()
    {
        $data = [
            'name' => 'pilates test class',
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-03',
            'capacity' => 15
        ];

        $result = $this->classService->createClass($data);

        $this->assertCount(3, $result); // 3 days from start_date to end_date
        $this->assertEquals('2025-01-01', $result[0]['date']);
        $this->assertEquals(15, $result[0]['capacity']);
        $this->assertEquals('pilates test class', $result[0]['name']);
    }
}
