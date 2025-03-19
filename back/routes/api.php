<?php

use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\SessionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('movies', MovieController::class)->only(['index', 'show']);
    Route::apiResource('sessions', SessionController::class)->only(['index', 'show']);
});