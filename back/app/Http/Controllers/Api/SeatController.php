<?php

namespace App\Http\Controllers\Api;

use App\Models\Seat;
use App\Models\CinemaSession;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SeatController extends Controller
{
    /**
     * Obtener los asientos de una sesión específica.
     *
     * @param  int  $sessionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($sessionId)
    {
        // Verificar si la sesión de cine existe
        $session = CinemaSession::all();
        

        // Retornar los asientos como respuesta JSON
        return response()->json($session);
    }
}