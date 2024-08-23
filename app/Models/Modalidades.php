<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modalidades extends Model
{
    use HasFactory;

    protected $fillable = [
        'foto_modalidade',
        'nome_modalidade',
        'descricao_modalidade',
    ];

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuarios_modalidades', 'modalidade_id', 'usuario_id');
    }
}
