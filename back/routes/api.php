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
Route::post('listGrupoAf', [\App\Http\Controllers\AfiliadoController::class,'listGrupoAf']);
