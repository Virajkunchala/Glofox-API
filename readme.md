# Booking API

This project provides an API for managing bookings for a platform such as gyms, studios, and boutiques. It allows users to make bookings based on available classes, and it validates inputs such as class date and required fields.

## Features

- **Create Classes**: Allows owners to create classes for their members with basic details like class name, start date, end date, and capacity.

- **Book Classes**: Allows members to book classes by providing their name and the date they want to attend.


# Project Structure

glofox/
│
├── app/
│   ├── Controllers/
│   │   ├── BookingController.php           # Booking controller handling the HTTP requests
│   │   └── ClassController.php            # Controller for class-related operations
│   ├── Services/
│   │   ├── BookingService.php             # Business logic for booking operations
│   │   └── ClassService.php               # Business logic for class operations
│   ├── Utils/
│   │   └── FileStorage.php                # Utility class for file storage (read/write JSON)
│   ├── Storage/
│   │   ├── booking_data.json              # JSON file storing the bookings
│   │   └── class_data.json                # JSON file storing the class data
│   └── index.php                            # entry point for the application (PHP server)
│                                               
│
├── tests/
│   ├── Controllers/
│   │   ├── BookingControllerTest.php      # PHPUnit test for BookingController
│   │   └── ClassControllerTest.php        # PHPUnit test for ClassController
│   ├── Services/
│   │   ├── BookingServiceTest.php         # PHPUnit test for BookingService
│   │   └── ClassServiceTest.php           # PHPUnit test for ClassService
│   ├── Utils/
│   │   └── FileStorageTest.php            # PHPUnit test for FileStorage utility
│   
│
├── composer.json                          # Composer configuration file
├── composer.lock                          # Lock file for managing dependencies
└── README.md                              # Project setup and documentation




## Set up instrcutions

### 1.clone


## install dependencies
```bash

    composer install

  ```

## Running the application
To run the application, navigate to the app directory and start a PHP built-in server:

```bash

    php -S localhost:8000 -t app

  ```

This will serve the API at http://localhost:8000

## Data Storage
For simplicity, this application does not use a database. Instead, it saves class and booking data in two JSON files:

class_data.json: Stores information about the created classes.
booking_data.json: Stores information about bookings made for classes.
These files are located in the app/Storage/ directory and are used to persist the data. When a new class is created or a booking is made, the information is saved to these files.

## End points

# 1. Create a Class (/classes)
Method: POST

Description:
This API allows the studio owner to create a class by specifying its name, start date, end date, and capacity. The class will automatically create multiple sessions, one for each day between the start and end dates.

```json

{
    "name": "Pilates",
    "start_date": "2025-01-01",
    "end_date": "2025-01-10",
    "capacity": 10
}

  ```

Response (Success):

```json

{
    "message": "Classes created successfully",
    "class": {
        "name": "Pilates",
        "start_date": "2025-01-01",
        "end_date": "2025-01-10",
        "capacity": 10
    }
}
  ```

# 2. Book a Class (/bookings)
Method: POST
Description:
This API allows a member to book a class for a specific date. The member's name and the date for booking are required.

Request Body (JSON):
```json

{
    "name": "test name",
    "date": "2025-01-05"
}
  ```

Response (Success):

```json

{
    "message": "Booking created successfully",
    "booking": {
        "name": "test name",
        "date": "2025-01-05"
    }
}
  ```

## Running Tests
Unit Tests
The project includes unit tests to validate the functionality of the application. To run the tests,


```bash

./vendor/bin/phpunit tests/

  ```
## Running tests for specific components


```bash

./vendor/bin/phpunit  tests/Controllers/ClassControllerTest.php

  ```


