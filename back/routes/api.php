<?php
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SeatController;

Route::prefix('v1')->group(function () {
    Route::apiResource('movies', MovieController::class)->only(['index', 'show']);
    Route::apiResource('sessions', SessionController::class)->only(['index', 'show']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('tickets', [TicketController::class, 'index']);
    Route::post('/buy-tickets', [TicketController::class, 'store']);
});
Route::post('/tickets', [TicketController::class, 'store']);
// Ruta para obtener los asientos de una sesión específica
Route::get('sessions/{sessionId}/seats', [SeatController::class, 'index']);
Route::get('sessions/', [SessionController::class, 'index']);
Route::get('/v1/sessions', [SessionController::class, 'index']);
Route::get('/v1/sessions/{session}', [SessionController::class, 'show']);