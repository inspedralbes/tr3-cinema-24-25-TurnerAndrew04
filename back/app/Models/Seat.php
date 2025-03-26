<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Seat extends Model
{
    protected $fillable = [
        'row',
        'number',
        'isVip',
        'isOccupied',
    ];

    protected $casts = [
        'is_vip' => 'boolean',
        'is_occupied' => 'boolean', // Asegúrate de que `is_occupied` esté definido correctamente en la tabla
    ];

    /**
     * Relación de muchos a muchos con CinemaSession.
     * Esta relación conecta el asiento con las sesiones de cine a través de la tabla intermedia 'session_seats'.
     */
    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(CinemaSession::class, 'session_seats')  // Asegúrate de que el nombre de la tabla intermedia sea 'session_seats'
            ->withPivot('is_occupied')
            ->withTimestamps();
    }

    /**
     * Método para verificar si un asiento está ocupado en una sesión específica.
     */
    public function isOccupied(CinemaSession $session): bool
    
        {
            // Verifica si la relación existe en la tabla intermedia 'session_seats' para esta sesión con el asiento en la tabla intermedia
            return $this->sessions()->where('cinema_session_id', $session->id)->wherePivot('is_occupied', true)->exists();
        }
}