<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'cinema_session_id',
        'seat_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'price',
        'isOccupied',
    ];


    // Relación con CinemaSession
    public function cinemaSession()
    {
        return $this->belongsTo(CinemaSession::class);
    }


    public function session(): BelongsTo
    {
        return $this->belongsTo(CinemaSession::class, 'cinema_session_id');
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}