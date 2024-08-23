<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidades extends Model
{
    use HasFactory;

    protected $fillable = [
        'imagem_unidade',
        'nome_unidade',
        'endereco',
        'grade',
        'descricao',
    ];
}
