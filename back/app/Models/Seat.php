<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Seat extends Model
{
    protected $fillable = [
        'row',
        'number',
        'is_vip', // Corrección: Usar snake_case
        'is_occupied', // Corrección: Usar snake_case
    ];

    protected $casts = [
        'is_vip' => 'boolean',
        'is_occupied' => 'boolean',
    ];

    /**
     * Relación de muchos a muchos con CinemaSession.
     * Conecta los asientos con las sesiones de cine a través de la tabla intermedia 'session_seat'.
     */
    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(CinemaSession::class, 'session_seat') // Corrección: Nombre correcto de la tabla intermedia
            ->withPivot('is_occupied', 'is_vip') // Agregar ambos valores en el pivot
            ->withTimestamps();
    }

    /**
     * Método para verificar si un asiento está ocupado en una sesión específica.
     */
    public function isOccupied(CinemaSession $session): bool
    {
        // Verifica si la relación existe en la tabla intermedia 'session_seat' para esta sesión
        return $this->sessions()
            ->where('cinema_session_id', $session->id)
            ->wherePivot('is_occupied', true)
            ->exists();
    }
}