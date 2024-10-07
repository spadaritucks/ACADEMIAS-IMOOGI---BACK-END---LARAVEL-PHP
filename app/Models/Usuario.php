<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    

    protected $fillable = [
        'foto_usuario',
        'tipo_usuario',
        'nome',
        'email',
        'data_nascimento',
        'cpf',
        'rg',
        'telefone',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'password',
    ];

    protected $hidden = [
        'password'
    ];

 
    
   protected function casts(): array
   {
       return [
           'email_verified_at' => 'datetime',
           'password' => 'hashed',
       ];
   }

   public function Contratos()
   {
       return $this->hasOne(Contratos::class, 'usuario_id');
   }

   public function UsuarioModalidades()
   {
       return $this->belongsToMany(Modalidades::class, 'usuarios_modalidades', 'usuario_id','modalidade_id');
   }

   public function DadosFuncionario()
   {
       return $this->hasOne(DadosFuncionario::class, 'usuario_id');
   }

}


