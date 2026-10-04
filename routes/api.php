<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RfidController;

Route::middleware('throttle:60,1')->group(function () {
    Route::post('/rfid/register', [RfidController::class, 'register']);
    Route::get('/rfid/last', [RfidController::class, 'last']);
    Route::post('/rfid/absen', [RfidController::class, 'store']);
    Route::get('/rfid/count-hadir', [RfidController::class, 'countHadir']);
});

