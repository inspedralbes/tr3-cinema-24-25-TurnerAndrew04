<?php

use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('movies', MovieController::class)->only(['index', 'show']);
    Route::apiResource('sessions', SessionController::class)->only(['index', 'show']);
    Route::post('tickets', [TicketController::class, 'store']);
    Route::get('tickets', [TicketController::class, 'index']);
});