<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'cinema_session_id',
        'seat_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'price',
        'is_vip',
        'is_occupied'
    ];

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