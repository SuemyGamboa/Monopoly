<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Modelo especial para el Login
use Illuminate\Foundation\Auth\User as Authenticatable;

// class Usuarios extends Model
class Usuarios extends Authenticatable
{
    protected $table = 'usuario';
    protected $primaryKey = 'id';
    public $timestamps = false;
}