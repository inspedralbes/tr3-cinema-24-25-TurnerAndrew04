<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CinemaSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Obtener todas las sesiones con sus películas.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Inicia la consulta para obtener sesiones con sus películas.
        $query = CinemaSession::with(['movie']);

        // Filtra las sesiones por fecha si está presente en la solicitud.
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        // Obtiene todas las sesiones.
        $sessions = $query->get();

        // Devuelve las sesiones con sus datos de la película.
        return response()->json([
            'sessions' => $sessions->map(function ($session) {
                return [
                    'session' => $session, // La sesión de cine
                    'movie' => $session->movie, // Película asociada
                ];
            })
        ]);
    }

    /**
     * Obtener una sesión específica con sus detalles y asientos.
     *
     * @param CinemaSession $session
     * @return JsonResponse
     */
    public function show(CinemaSession $session): JsonResponse
    {
        // Carga la película y los asientos asociados con la sesión.
        $session->load(['movie', 'seats']);

        // Mapea los asientos de la sesión con los datos relevantes, incluyendo `is_occupied`.
        $seats = $session->seats->map(function ($seat) {
            return [
                'row' => $seat->row,
                'number' => $seat->number,
                'is_vip' => $seat->is_vip,
                'is_occupied' => $seat->pivot->is_occupied, // Obtiene el estado de ocupación desde la tabla intermedia
            ];
        });

        // Devuelve la sesión con la película y los asientos.
        return response()->json([
            'session' => $session,
            'movie' => $session->movie,
            'seats' => $seats
        ]);
    }
}