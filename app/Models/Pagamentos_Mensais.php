<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamentos_Mensais extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'comprovante',
        'valor_pago',
        'data_pagamento',
        'comentario',
        'comentario_adm'
    ];

    protected $table = "pagamentos_mensais";
}
