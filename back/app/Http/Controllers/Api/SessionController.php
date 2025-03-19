<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CinemaSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = CinemaSession::with(['movie']);

        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        $sessions = $query->get();

        return response()->json([
            'sessions' => $sessions->map(function ($session) {
                return [
                    'session' => $session,
                    'movie' => $session->movie
                ];
            })
        ]);
    }

    public function show(CinemaSession $session): JsonResponse
    {
        $session->load(['movie', 'seats']);
        
        $seats = $session->seats->map(function ($seat) {
            return [
                'row' => $seat->row,
                'number' => $seat->number,
                'isVip' => $seat->is_vip,
                'isOccupied' => $seat->pivot->is_occupied
            ];
        });

        return response()->json([
            'session' => $session,
            'movie' => $session->movie,
            'seats' => $seats
        ]);
    }
}