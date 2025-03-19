<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Seat extends Model
{
    protected $fillable = [
        'row',
        'number',
        'is_vip'
    ];

    protected $casts = [
        'is_vip' => 'boolean'
    ];

    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(CinemaSession::class, 'session_seats')
            ->withPivot('is_occupied')
            ->withTimestamps();
    }
}