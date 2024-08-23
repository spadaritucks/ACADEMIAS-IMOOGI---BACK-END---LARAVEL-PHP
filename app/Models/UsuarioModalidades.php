<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioModalidades extends Model
{
    use HasFactory;


    protected $fillable = [
        'usuario_id',
        'modalidade_id',

    ];

    protected $table = 'usuarios_modalidades';

    

}
