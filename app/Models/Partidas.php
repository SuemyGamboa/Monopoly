<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partidas extends Model
{
    protected $table = 'partida';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public function jugadores()
    {
        return $this->belongsToMany(Jugadores::class, 'partidajugador', 'idpartida', 'idjugador')
                    ->withPivot('dinero', 'turno', 'posicion', 'status');
    }
    
      
}