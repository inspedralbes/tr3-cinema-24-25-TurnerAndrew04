<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', // IMDB ID
        'title',
        'year',
        'poster',
        'plot',
        'duration'
    ];

    public function sessions(): HasMany
    {
        return $this->hasMany(CinemaSession::class);
    }
}