<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planos extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_plano',
        'duracao',
        'valor_matricula',
        'valor_mensal',
        'valor_total',
        'num_modalidades',
        'status'
    ];
}
