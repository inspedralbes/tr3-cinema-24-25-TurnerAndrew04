<?php

namespace App\Http\Controllers\Api;

use App\Models\Ticket;
use App\Models\CinemaSession;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $session_id = $request->session_id;
            $totalPrice = $request->price;
            $seats = $request->seats;

            foreach ($seats as $seat) {
                $seatRecord = Seat::where('id', $seat['id'])->first();

                if (!$seatRecord) {
                    return response()->json(['message' => "El asiento con ID {$seat['id']} no existe"], 400);
                }

                // Verificar si el asiento ya está ocupado
                $existingTicket = Ticket::where('cinema_session_id', $session_id)
                    ->where('seat_id', $seatRecord->id)
                    ->first();

                if ($existingTicket) {
                    return response()->json(['message' => "El asiento con ID {$seat['id']} ya está reservado"], 400);
                }
            }

            // Crear y guardar los tickets
            foreach ($seats as $seat) {
                $seatRecord = Seat::where('id', $seat['id'])->first();

                $ticket = new Ticket([
                    'cinema_session_id' => $session_id,
                    'seat_id' => $seatRecord->id,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'price' => $totalPrice / count($seats),
                    'isOccupied' => true
                ]);

                $ticket->save();

                // 🔥 Marcar el asiento como ocupado en la base de datos
                $seatRecord->update(['is_occupied' => true]);
            }

            DB::commit();
            return response()->json(['message' => 'Tickets comprados con éxito'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Hubo un error al procesar la compra', 'details' => $e->getMessage()], 500);
        }
    }
    public function getByEmail(Request $request)
    {
        $email = $request->query('email');
    
        if (!$email) {
            return response()->json(['error' => 'Email requerido'], 400);
        }
    
        $tickets = Ticket::with(['cinemaSession.movie'])->where('customer_email', $email)->get();
    
        return response()->json(['tickets' => $tickets]);
    }
}
