<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LugaresController;
use App\Http\Controllers\FormatosController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ResultadosController;
use App\Http\Controllers\CandidatosController;
use App\Http\Controllers\RegistrarVotosController;

//Usuarios
Route::post('registro',[AuthController::class, 'registro']);
//Candidatos
Route::get('candidatos', [CandidatosController::class, 'index']);
Route::post('candidatos', [CandidatosController::class, 'store']);
Route::post('candidatos/{id}', [CandidatosController::class, 'update']);
Route::delete('candidatos/{id}', [CandidatosController::class, 'delete']);

//Lugares
Route::get('lugares', [LugaresController::class, 'index']);
Route::post('lugares', [LugaresController::class, 'store']);
Route::post('lugares/{id}', [LugaresController::class, 'update']);
Route::delete('lugares/{id}', [LugaresController::class, 'delete']);

//Formatos
Route::get('/formatos', [FormatosController::class, 'index']);
Route::post('/formatos', [FormatosController::class, 'store']);
Route::post('/formatos/{id}', [FormatosController::class, 'update']);

//Usuarios
Route::get('/usuarios', [UsuariosController::class, 'index']);
Route::get('/usuarios/{id}/formatos', [UsuariosController::class, 'formatosusuario']);
Route::post('/usuarios', [UsuariosController::class, 'store']);
Route::post('/usuarios/{id}', [UsuariosController::class, 'update']);

//Registro votos
Route::post('/votos/{id}/formato', [RegistrarVotosController::class, 'storeUpdate']);

//Resultados
Route::get('resultados', [ResultadosController::class, 'index']);
Route::get('ganador', [ResultadosController::class, 'ganador']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
