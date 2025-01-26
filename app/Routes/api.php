<?php
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\ClassController;
use App\Controllers\BookingController;

$app->get('/', [ClassController::class, 'index']); // Create class

$app->post('/classes', [ClassController::class, 'createClass']); // Create class

$app->post('/bookings', [BookingController::class, 'createBooking']); // Get all classes