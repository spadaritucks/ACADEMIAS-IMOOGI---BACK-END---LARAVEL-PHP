<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ModalidadesController;
use App\Http\Controllers\PlanosController;
use App\Http\Controllers\UnidadesController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['cors'])->group(function () {
    //Roteamento dos Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);
    Route::post('/usuarios', [UsuarioController::class, 'store']);
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);


    //Roteamento dos Planos
    Route::get('/planos', [PlanosController::class, 'index']);
    Route::get('/planos/{id}', [PlanosController::class, 'show']);
    Route::post('/planos', [PlanosController::class, 'store']);
    Route::put('/planos/{id}', [PlanosController::class, 'update']);
    Route::delete('/planos/{id}', [PlanosController::class, 'destroy']);

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
    Route::get('/unidades/{id}', [UnidadesController::class, 'destroy']);

    //Rota de Autenticação
    Route::post('/login', [LoginController::class, 'login']);
});

Route::options('/{any}', function () {
    return response()->json([], 200)
        ->header('Access-Control-Allow-Origin', 'https://academiasimoogi.com.br')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token, Authorization')
        ->header('Access-Control-Allow-Credentials', 'true');
})->where('any', '.*');
