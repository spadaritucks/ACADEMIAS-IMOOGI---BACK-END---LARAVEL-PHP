<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuariosPacks extends Model
{
    use HasFactory;

    protected $fillable = [
       'usuario_id',
       'packs_id'
    ];

    protected $table = 'usuarios_packs';
}
