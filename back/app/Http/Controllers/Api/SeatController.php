<?php

namespace App\Http\Controllers\Api;

use App\Models\CinemaSession;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class SeatController extends Controller
{
    /**
     * Obtener los asientos de una sesión específica.
     *
     * @param  int  $sessionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($sessionId): JsonResponse
    {
        // Buscar la sesión en la base de datos con sus asientos
        $session = CinemaSession::with('seats')->find($sessionId);

        // Si la sesión no existe, devolver un error 404
        if (!$session) {
            return response()->json(['message' => 'Sesión no encontrada'], 404);
        }

        // Mapear los asientos y devolverlos en formato JSON
        return response()->json($session->seats->map(function ($seat) {
            return [
                'id' => $seat->id,
                'row' => $seat->row,
                'number' => $seat->number,
                'is_vip' => $seat->is_vip,  // Corrección: usar el pivot correcto
                'is_occupied' => $seat->is_occupied // Corrección: usar el pivot correcto
            ];
        }));
    }
}