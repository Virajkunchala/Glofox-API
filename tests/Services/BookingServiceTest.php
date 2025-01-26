<?php
use PHPUnit\Framework\TestCase;
use App\Services\BookingService;
use App\Utils\FileStorage;

class BookingServiceTest extends TestCase
{
    private $bookingService;
    private $mockBookingStorage;
    private $mockClassStorage;

    protected function setUp(): void
    {
        // Create mocks for FileStorage
        $this->mockBookingStorage = $this->createMock(FileStorage::class);
        $this->mockClassStorage = $this->createMock(FileStorage::class);
        
        // Instantiate the BookingService with mocks
        $this->bookingService = new BookingService($this->mockBookingStorage, $this->mockClassStorage);
    }

    public function testValidateBookingDateValid()
    {
        // Mock the classStorage's read method to return classes for the date
        $this->mockClassStorage->method('read')->willReturn([          
            ['name' => 'pilates test class ', 'date' => '2025-01-05']
        ]);

        $data = [
            'name' => 'Viraj',
            'date' => '2025-01-05'
        ];

        // Call the validateBookingDate method
        $result = $this->bookingService->validateBookingDate($data);

        // Assert that there are no validation errors
        $this->assertEmpty($result);
    }

    public function testValidateBookingDateInvalidMissingField()
    {
        $data = [
            'name' => '',  // Missing name
            'date' => '2025-01-05'
        ];

        $result = $this->bookingService->validateBookingDate($data);

        $this->assertNotEmpty($result);
        $this->assertContains('Missing required field: name', $result);
    }

    public function testValidateBookingDateInvalidDateFormat()
    {
        $data = [
            'name' => 'Viraj',
            'date' => 'invalid-date'  // Invalid date format
        ];

        $result = $this->bookingService->validateBookingDate($data);

        $this->assertNotEmpty($result);
        $this->assertContains('Invalid date format. Use YYYY-MM-DD.', $result);
    }

    public function testValidateBookingDateNoClassForDate()
    {
        // Mock class storage to return an empty array (no classes for the date)
        $this->mockClassStorage->method('read')->willReturn([]);

        $data = [
            'name' => 'Viraj',
            'date' => '2025-01-10'  // No class exists for this date
        ];

        $result = $this->bookingService->validateBookingDate($data);

        $this->assertNotEmpty($result);
        $this->assertContains('No class exists for the date', $result);
    }

    public function testCreateBooking()
    {
        // Mock the append method on bookingStorage to prevent file manipulation
        $this->mockBookingStorage->expects($this->once())
            ->method('append')
            ->with($this->isType('array'));

        $data = [
            'name' => 'Viraj',
            'date' => '2025-01-05'
        ];

        // Call createBooking and assert the result
        $result = $this->bookingService->createBooking($data);

        $this->assertEquals('Viraj', $result['name']);
        $this->assertEquals('2025-01-05', $result['date']);
    }
}
