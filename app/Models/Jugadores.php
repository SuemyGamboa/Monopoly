<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jugadores extends Model
{
    protected $table = 'jugador';
    
  
    public $timestamps = false;

    public function partidas()
    {
        return $this->belongsToMany(Partidas::class, 'partidajugador', 'idjugador', 'idpartida')
            ->withPivot('dinero', 'turno', 'posicion', 'status');
    }
    
}