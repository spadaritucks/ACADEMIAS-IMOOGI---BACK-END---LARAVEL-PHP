<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aulas extends Model
{
    use HasFactory;
    
    protected $fillable = [

        'modalidade_id',
        'horario',
        'dia_semana',
        'limite_alunos'
    ];
}
