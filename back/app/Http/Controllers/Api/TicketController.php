<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\CinemaSession;
use App\Models\Seat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TicketController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:cinema_sessions,id',
            'seats' => 'required|array|min:1|max:10',
            'seats.*.row' => 'required|string',
            'seats.*.number' => 'required|integer|min:1|max:10',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:255',
        ]);

        try {
            return DB::transaction(function () use ($validated, $request) {
                $session = CinemaSession::findOrFail($validated['session_id']);
                $tickets = [];

                foreach ($validated['seats'] as $seatData) {
                    // Find the seat
                    $seat = Seat::where('row', $seatData['row'])
                        ->where('number', $seatData['number'])
                        ->firstOrFail();

                    // Check if seat is already occupied
                    $isOccupied = Ticket::where('cinema_session_id', $session->id)
                        ->where('seat_id', $seat->id)
                        ->exists();

                    if ($isOccupied) {
                        throw ValidationException::withMessages([
                            'seats' => "Seat {$seat->row}{$seat->number} is already occupied"
                        ]);
                    }

                    // Calculate price based on VIP status
                    $price = $seat->is_vip ? 8 : 6;

                    // Create ticket
                    $ticket = Ticket::create([
                        'cinema_session_id' => $session->id,
                        'seat_id' => $seat->id,
                        'user_id' => $request->user()?->id,
                        'customer_name' => $validated['customer_name'],
                        'customer_email' => $validated['customer_email'],
                        'customer_phone' => $validated['customer_phone'],
                        'price' => $price
                    ]);

                    $tickets[] = $ticket;
                }

                return response()->json([
                    'message' => 'Tickets purchased successfully',
                    'tickets' => $tickets
                ], 201);
            });
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error purchasing tickets',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $tickets = Ticket::with(['session.movie'])
            ->where('customer_email', $request->email)
            ->get();

        return response()->json([
            'tickets' => $tickets
        ]);
    }
}