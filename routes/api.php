<?php

use App\Http\Controllers\AulasController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ModalidadesController;
use App\Http\Controllers\PacksController;
use App\Http\Controllers\PlanosController;
use App\Http\Controllers\ReservasController;
use App\Http\Controllers\UnidadesController;
use App\Http\Controllers\UserModalidadeEditController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Roteamento dos Usuarios
Route::get('/usuarios', [UsuarioController::class, 'index']);
Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);
Route::post('/usuarios', [UsuarioController::class, 'store']);
Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
Route::put('/user_modalidade/{id}',[UserModalidadeEditController::class, 'update']);
Route::put('/usuario_client/{id}', [UsuarioController::class, 'updateClientUser']);
route::put('/user_password/{id}',[UsuarioController::class, 'resetPassword']);
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);



Route::get('/image/{filename}', [ImageController::class, 'getUserImage']);




//Roteamento dos Planos
Route::get('/planos', [PlanosController::class, 'index']);
Route::get('/planos/{id}', [PlanosController::class, 'show']);
Route::post('/planos', [PlanosController::class, 'store']);
Route::put('/planos/{id}', [PlanosController::class, 'update']);
Route::delete('/planos/{id}', [PlanosController::class, 'destroy']);

Route::get('/packs', [PacksController::class, 'index']);
Route::post('/packs', [PacksController::class, 'store']);
Route::put('/packs/{id}', [PacksController::class, 'update']);
Route::delete('/packs/{id}', [PacksController::class, 'destroy']);

//Roteamento das Modalidades
Route::get('/modalidades', [ModalidadesController::class, 'index']);
Route::get('/modalidades/{id}', [ModalidadesController::class, 'show']);
Route::post('/modalidades', [ModalidadesController::class, 'store']);
Route::put('/modalidades/{id}', [ModalidadesController::class, 'update']);
Route::delete('/modalidades/{id}', [ModalidadesController::class, 'destroy']);

//Rotas das Unidades
Route::get('/unidades', [UnidadesController::class, 'index']);
Route::get('/unidades/{id}', [UnidadesController::class, 'show']);
Route::post('/unidades', [UnidadesController::class, 'store']);
Route::put('/unidades/{id}', [UnidadesController::class, 'update']);
Route::delete('/unidades/{id}', [UnidadesController::class, 'destroy']);

//Rota de Autenticação
Route::post('/login', [LoginController::class, 'login']);



//Rotas de Reservas de Aula
Route::get('/reservas', [ReservasController::class, 'index']);
Route::post('/reservas', [ReservasController::class, 'store']);
Route::delete('/reservas/{id}', [ReservasController::class, 'destroy']);

//Rotas de Gerenciamento de Aulas

Route::get('/aulas', [AulasController::class, 'index']);
Route::post('/aulas', [AulasController::class, 'store']);
Route::put('/aulas/{id}', [AulasController::class, 'update']);
Route::delete('/aulas/{id}', [AulasController::class, 'destroy']);




