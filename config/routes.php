<?php

use App\Controllers\{UserController, TripController, AgencyController};


return [

    // -------- Auth --------
    ['POST', '/api/login', [UserController::class, 'loginController'], []],
    ['POST', '/api/logout', [UserController::class, 'logoutController'],    ['auth', 'csrf']],

    // -------- Users --------
    ['GET', '/api/users', [UserController::class, 'findAllController'], ['auth', 'admin']],
    ['GET', '/api/users/by-email', [UserController::class, 'findByEmailController'], ['auth']],
    ['GET', '/api/users/by-id', [UserController::class, 'findByIdController'], ['auth']],

    // -------- Agencies --------
    ['GET', '/api/agencies', [AgencyController::class, 'findAllController'], []],
    ['POST', '/api/agencies/search', [AgencyController::class, 'findByNameController'], []],
    ['POST', '/api/agencies', [AgencyController::class, 'createController'], ['auth', 'admin', 'csrf']],
    ['GET', '/api/agencies/by-id', [AgencyController::class, 'findByIdController'], ['auth']],
    ['POST', '/api/agencies/update', [AgencyController::class, 'updateController'], ['auth', 'admin', 'csrf']],
    ['POST', '/api/agencies/delete', [AgencyController::class, 'deleteController'], ['auth', 'admin', 'csrf']],

    // -------- Trips --------
    ['GET', '/api/trips', [TripController::class, 'findAllController'], []],
    ['POST', '/api/trips/actuals', [TripController::class, 'findAvailablesTripsController'], []],
    ['POST', '/api/trips', [TripController::class, 'createController'], ['auth', 'csrf']],
    ['GET', '/api/trips/by-id', [TripController::class, 'findByIdController'], ['auth']],
    ['POST', '/api/trips/update', [TripController::class, 'updateController'], ['auth', 'csrf']],
    ['POST', '/api/trips/delete', [TripController::class, 'deleteController'], ['auth', 'csrf']]
];