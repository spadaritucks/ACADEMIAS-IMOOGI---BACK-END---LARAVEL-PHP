<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservas extends Model
{
    use HasFactory;

   protected $fillable = [
        'usuario_id',
        'modalidade_id',
        'horario',
        'dia_semana',
        'data',
    ];

    
}
