<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::resource('afiliado', \App\Http\Controllers\AfiliadoController::class);
Route::resource('grupo', \App\Http\Controllers\GrupoController::class);
Route::resource('vehiculo', \App\Http\Controllers\VehiculoController::class);
Route::resource('pago', \App\Http\Controllers\PagoController::class);
Route::post('anularPago', [\App\Http\Controllers\PagoController::class,'anularPago']);
Route::post('consultapago', [\App\Http\Controllers\PagoController::class,'consultapago']);
Route::post('datoimp', [\App\Http\Controllers\PagoController::class,'datoimp']);
Route::post('listGrupoAf', [\App\Http\Controllers\AfiliadoController::class,'listGrupoAf']);
Route::post('reporte', [\App\Http\Controllers\AsistenciaController::class,'reporte']);
Route::post('pagoConsulta', [\App\Http\Controllers\PagoController::class,'pagoConsulta']);
Route::post('login', [App\Http\Controllers\UserController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('me', [App\Http\Controllers\UserController::class, 'me']);
    Route::post('logout', [App\Http\Controllers\UserController::class, 'logout']);
    Route::apiResource('user', App\Http\Controllers\UserController::class);
    Route::put('/updatePassword/{user}',[\App\Http\Controllers\UserController::class,'updatePassword']);
    Route::put('/updatepermisos/{user}',[\App\Http\Controllers\UserController::class,'updatepermisos']);
    Route::resource('/permiso',\App\Http\Controllers\PermisoController::class);
});
