<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DadosFuncionario extends Model
{
    use HasFactory;


    protected $fillable = [
        'usuario_id',
        'tipo_funcionario',
        'cargo',
        'atividades',
    ];
    
    protected $table = 'dados_funcionario';
}
