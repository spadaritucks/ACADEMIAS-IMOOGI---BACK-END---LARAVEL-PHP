<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contratos extends Model
{
    use HasFactory;


    protected $fillable = [
        'usuario_id',
        'planos_id',
        'packs_id',
        'data_inicio',
        'data_renovacao',
        'data_vencimento',
        'comprovante',
        'valor_plano',
        'desconto',
        'parcelas',
        'observacoes'

    ];

    
}
