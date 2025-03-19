<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CinemaSession extends Model
{
    protected $fillable = [
        'movie_id',
        'date',
        'time',
        'is_special_day'
    ];

    protected $casts = [
        'date' => 'date',
        'is_special_day' => 'boolean'
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function seats(): BelongsToMany
    {
        return $this->belongsToMany(Seat::class, 'session_seats')
            ->withPivot('is_occupied')
            ->withTimestamps();
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}