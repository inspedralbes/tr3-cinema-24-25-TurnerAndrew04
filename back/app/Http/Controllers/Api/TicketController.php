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
        // Validación de los datos del ticket
        dd($request);
        $validated = $request->validate([
            'session_id' => 'required|exists:cinema_sessions,id',
            'seats' => 'required|array|min:1', // Aseguramos que se envíen varios asientos
            'seats.*.row' => 'required|string',
            'seats.*.number' => 'required|integer',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'price' => 'required|numeric', // Aseguramos que el precio total sea enviado
            // 'isVip' => 'required|boolean',
            // 'isOccupied' => 'required|boolean',
        ]);

        DB::beginTransaction(); // Iniciar la transacción

        try {
            $session_id = $validated['session_id'];
            $totalPrice = $validated['price'];
            $seats = $validated['seats'];

            // Verificar la disponibilidad de cada asiento
            foreach ($seats as $seat) {
                $seatRecord = Seat::where('cinema_session_id', $session_id)
                                  ->where('row', $seat['row'])
                                  ->where('number', $seat['number'])
                                  ->first();

                if (!$seatRecord) {
                    return response()->json(['message' => "El asiento {$seat['row']}{$seat['number']} no existe"], 400);
                }

                // Verificar si el asiento ya está ocupado
                $existingTicket = Ticket::where('cinema_session_id', $session_id)
                                        ->where('seat_id', $seatRecord->id)
                                        ->first();

                if ($existingTicket) {
                    return response()->json(['message' => "El asiento {$seat['row']}{$seat['number']} ya está reservado"], 400);
                }
            }

            // Crear los tickets
            $tickets = [];
            foreach ($seats as $seat) {
                $seatRecord = Seat::where('cinema_session_id', $session_id)
                                  ->where('row', $seat['row'])
                                  ->where('number', $seat['number'])
                                  ->first();

                $tickets[] = new Ticket([
                    'cinema_session_id' => $session_id,
                    'seat_id' => $seatRecord->id,
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'price' => $totalPrice / count($seats), // Dividir el precio entre todos los asientos seleccionados
                    // 'isVip' => $validated['isVip'],
                    'isOccupied' => true
                ]);

                // Marcar el asiento como ocupado
                $seatRecord->update(['is_occupied' => true]);
            }

            // Guardar todos los tickets en la base de datos
            Ticket::insert($tickets);

            // Confirmar la transacción
            DB::commit();

            return response()->json(['message' => 'Tickets comprados con éxito'], 200);
        } catch (\Exception $e) {
            // Si ocurre un error, revertir la transacción
            DB::rollBack();
            return response()->json(['error' => 'Hubo un error al procesar la compra', 'details' => $e->getMessage()], 500);
        }
    }
}