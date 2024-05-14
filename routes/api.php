<?php

use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::prefix('vehicles')->group(function () {
    Route::get('/search', [VehicleController::class, 'search']);
    Route::get('/filters', [VehicleController::class, 'filters']);
});
